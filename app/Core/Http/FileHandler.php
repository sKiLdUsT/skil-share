<?php

namespace Core\Http;

use DB\File;
use Core\Auth;
use DB\User;
use getID3;

class FileHandler extends Handler
{
    public function upload(\stdClass $request)
    {
        if (!isset($request->input->key))
        {
            if(!isset($request->input->token) || $request->input->token !== $_SESSION['token']) {
                http_response_code(401);
                (new SiteHandler())->upload($request, 'CSRF Token Mismatch!');
                return;
            }
        }
        $auth = Auth::check();
        if (!$auth) {
            try {
                $user = new User(['sharex_key', $request->input->key]);
                $_SESSION['auth'] = true;
                $_SESSION['uid'] = $user->id;
            } catch (\TypeError $e) {
                http_response_code(401);
                header('Location: /');
                return;
            }
        }
        if (!array_key_exists('file', $_FILES)) {
            http_response_code(400);
            (new SiteHandler())->upload($request, "No file attached or wrong key name");
            return;
        }
        $uploadFile = (object) $_FILES['file'];
        switch($uploadFile->error)
        {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                http_response_code(400);
                (new SiteHandler())->upload($request, 'File too big! (Must not be greater than 100MB)');
                break;
            case UPLOAD_ERR_PARTIAL:
                http_response_code(400);
                (new SiteHandler())->upload($request, 'File upload did not complete');
                break;
            case UPLOAD_ERR_NO_FILE:
                http_response_code(400);
                (new SiteHandler())->upload($request, 'Zero-length upload');
                break;
            default:
                http_response_code(400);
                (new SiteHandler())->upload($request, 'Internal error');
                break;
        }
        $file = new File();
        $file->name = escapeshellcmd($uploadFile->name);
        $file->store = md5_file($uploadFile->tmp_name);
        $mime = mime_content_type($uploadFile->tmp_name);
        $file->uid =  Auth::user()->id;
        switch(true)
        {
            case preg_match('/image\/.+/', $mime):
                $file->type = 1;
                break;
            case preg_match('/video\/.+/', $mime):
                $file->type = 2;
                break;
            case preg_match('/audio\/.+/', $mime):
                $file->type = 3;
                break;
            case preg_match('/text\/.+/', $mime):
                $file->type = 4;
                break;
            case preg_match('/application\/pdf/', $mime):
                $file->type = 5;
                break;
            default:
                $file->type = 0;
                break;
        }
        if (move_uploaded_file($uploadFile->tmp_name, __DIR__ . '/../../../storage/' . $file->store))
        {
            try {
                $file->save();
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode([
                        "success" => true,
                        "path" => "http" . ($_SERVER['HTTPS'] ? 's' : '') . "://" . $_SERVER['HTTP_HOST'] . "/" . $file->id
                    ]);
                    return;
                } else {
                    header('Location: /' . $file->id);
                }
                return;
            } catch (\ErrorException $e) {
                var_dump($e, true);
                exit();
                http_response_code(500);
                (new SiteHandler())->upload($request, "Unknown error");
                return;
            }
        } else {
            http_response_code(400);
            (new SiteHandler())->upload($request, "Malformed file.");
            return;
        }
    }

    public function display(\stdClass $request)
    {
        try {
            $file = new File(['id', substr($request->url, 1)]);
        } catch (\TypeError $e) {
            (new ErrorHandler())->error(404);
            return;
        }
        if (boolval($file->disabled) === true) {
            (new ErrorHandler())->error(410);
            return;
        }
        if (!file_exists(__DIR__ . '/../../../storage/' . $file->store))
        {
            $file->disabled = true;
            $file->save();
            (new ErrorHandler())->error(410);
            return;
        }
        if (isset($request->input->raw))
        {
            $this->sendFile($file);
            return;
        }
        $mime = mime_content_type(__DIR__ . '/../../../storage/' . $file->store);
        $size = round(filesize(__DIR__ . '/../../../storage/' . $file->store) / 1048576, 2);
        if ($file->type > 0) $info = (new getID3())->analyze(__DIR__ . '/../../../storage/' . $file->store);
        header('Cache-Control: public, must-revalidate');
        $uploader = (new \DB\User(['id', $file->uid]))->username;
        $auth = Auth::check();
        $title = $file->name;
        $assets = json_decode(file_get_contents(__DIR__ . '/../../../public/mix-manifest.json'), true);
        $assets = [
            'css' => $assets['/assets/css/app.css'],
            'manifest' => $assets['/assets/js/manifest.js'],
            'vendor' => $assets['/assets/js/vendor.js'],
            'js' => $assets['/assets/js/file.js'],
        ];
        require(__DIR__ . '/../../../resources/views/file.php');
    }

    private function sendFile(File $reqfile)
    {
        $filePath = __DIR__ . '/../../../storage/' . $reqfile->store;
        $mimeType = mime_content_type($filePath);
        $fileSize = filesize($filePath);
        if(file_exists($filePath)){
            $file = fopen($filePath, "r");
            $length = $fileSize;
            $start = 0;
            $end = $fileSize - 1;
            header("Content-Type: $mimeType");
            header('Cache-Control: public, must-revalidate, no-transform');
            header("Accept-Ranges: 0-" . $length);
            if($reqfile->type > 0){
                header('Content-Disposition: inline; filename="'.basename($reqfile->name).'"');
            }else{
                header('Content-Disposition: attachment; filename="'.basename($reqfile->name).'"');
            }
            if (isset($_SERVER['HTTP_RANGE'])) {
                $c_start = $start;
                $c_end = $end;
                list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                if (strpos($range, ',') !== false) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$fileSize");
                    exit;
                }
                if ($range == '-') {
                    $c_start = $fileSize - substr($range, 1);
                } else {
                    $range = explode('-', $range);
                    $c_start = $range[0];
                    $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $fileSize;
                }
                $c_end = ($c_end > $end) ? $end : $c_end;
                if ($c_start > $c_end || $c_start > $fileSize - 1 || $c_end >= $fileSize) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$fileSize");
                    exit;
                }
                $start = $c_start;
                $end = $c_end;
                $length = $end - $start + 1;
                fseek($file, $start);
                header('HTTP/1.1 206 Partial Content');
            }
            header("Content-Range: bytes $start-$end/$fileSize");
            header("Content-Length: " . $length);
            while (@ ob_end_flush()) ;
            while (!feof($file)) {
                print fread($file, 4096);
                @ flush();
            }
        }
    }
}