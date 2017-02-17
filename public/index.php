<?php

$baseDir = __DIR__ . '/../';
session_start();

try{
    require_once($baseDir . 'api.php');
    require_once($baseDir . 'config.php');

    $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    switch($request)
    {
        case '/':
            if(isset($_SESSION["login"]) && $_SESSION["login"] == true)
            {
                $mysqli = connectDB($config["db"]);
                $user = $mysqli->query("SELECT user FROM users WHERE id = '".$_SESSION["uid"]."'")->fetch_assoc()["user"];
            } else {
                $user = "Anonymous";
            }
            include($baseDir . 'templates/index.php');
            break;
        case '/user':
            header("Location: /");
            break;
        case '/login':
            switch($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $mysqli = connectDB($config["db"]);
                    if (isset($_POST['name']) && isset($_POST['pass']) && $uid = checkUser($mysqli, $_POST))
                    {
                        $_SESSION["login"] = true;
                        $_SESSION["uid"] = $uid;
                        if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
                        {
                            header('Content-Type: application/json');
                            echo '{"response": true}';
                        } else {
                            header('Location: /');
                        }
                    } else {
                        http_response_code(401);
                        if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
                        {
                            header('Content-Type: application/json');
                            echo '{"response": false}';
                        } else {
                            echo "Wrong credentials";
                        }
                    }
                    break;
                case 'GET':
                    if(isset($_SESSION["login"]) && $_SESSION["login"] == true)
                    {
                        header("Location: /");
                    }
                    include($baseDir . 'templates/login.php');
                    break;
            }
            break;
        case '/register':
            switch($_SERVER['REQUEST_METHOD'])
            {
                case 'GET':
                    if(isset($_SESSION["login"]) && $_SESSION["login"] == true)
                    {
                        header("Location: /");
                    }
                    include($baseDir . 'templates/register.php');
                    break;
                case 'POST':
                    if (isset($_POST['name']) && isset($_POST['password']))
                    {
                        $mysqli = connectDB($config["db"]);
                        $username = $_POST['name'];
                        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                        if($response = registerUser($username, $password, $mysqli))
                        {
                            $_SESSION["login"] = true;
                            $_SESSION["uid"] = $mysqli->query("SELECT id FROM users WHERE user = '".$username."'")->fetch_assoc()["id"];
                            if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
                            {
                                header('Content-Type: application/json');
                                echo "true";
                            } else {
                                header('Location: /');
                            }
                        } else
                        {
                            http_response_code(400);
                            if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
                            {
                                header('Content-Type: application/json');
                                echo "false";
                            } else {
                                echo $response;
                            }
                        }
                    }
                    else {
                        http_response_code(400);
                    }
            }
            break;
        case '/upload':
            switch($_SERVER['REQUEST_METHOD'])
            {
                case 'POST':
                    $mysqli = connectDB($config["db"]);
                    function doUpload($uid){
                        global $config, $mysqli;
                        $baseName = basename($_FILES['d']['name']);
                        $target = $config["app"]["targetPath"] . $baseName;
                        if (move_uploaded_file($_FILES['d']['tmp_name'], $target)) {
                            $md5 = md5_file($config["app"]["targetPath"] . $baseName);
                            $targetName = $md5 . "." . end(explode(".", $_FILES["d"]["name"]));
                            rename($config["app"]["targetPath"] . $baseName, $config["app"]["targetPath"] . $targetName);
                            if($targetID = registerID($targetName, $baseName, $uid, $mysqli)){
                                if(isset($_POST["isBrowser"])) header('Location: /'.$targetID);
                                echo $config["app"]["uploadHost"] . $targetID;
                            }else {
                                http_response_code(400);
                                die("error: cannot register id");
                            }
                        } else {
                            http_response_code(400);
                            die("error: malformed upload file");
                        }
                    }
                    if($_SESSION["login"] == true) {
                        doUpload($_SESSION["uid"]);
                    }
                    else if (isset($_POST['name']) && isset($_POST['pass']) && $uid = checkUser($mysqli, $_POST))
                    {
                        doUpload($uid);
                    } else {
                        http_response_code(403);
                    }
                    break;
                case 'GET':
                default:
                    if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true)
                    {
                        header('Location: /login');
                    } else {
                        $mysqli = connectDB($config["db"]);
                        $user = $mysqli->query("SELECT user FROM users WHERE id = '".$_SESSION["uid"]."'")->fetch_assoc()["user"];
                        include($baseDir . 'templates/upload.php');
                    }
                    break;
            }
            break;
        default:
            $mysqli = connectDB($config["db"]);
            if(!$data = queryID(substr($request, 1, 6), $mysqli)) die(include($baseDir . 'templates/404.php'));
            $data = $data[0];
            $filePath = $config["app"]["targetPath"] . $data["name"];
            $mimeType = mime_content_type($filePath);
            $fileSize = filesize($filePath);
            $user = $mysqli->query("SELECT user FROM users WHERE id = '".$_SESSION["uid"]."'")->fetch_assoc()["user"];
            $uUser = $mysqli->query("SELECT user FROM users WHERE id = '".$data["uid"]."'")->fetch_assoc()["user"];

            if(file_exists($filePath)){
                if(isset($_GET["raw"])){
                    $file = fopen($filePath, "r");
                    $length = $fileSize;
                    $start = 0;
                    $end = $fileSize - 1;
                    header("Content-Type: $mimeType");
                    header('Cache-Control: publio, must-revalidate');
                    header("Accept-Ranges: 0-" . $length);
                    if(preg_match("/(image|video|audio)\//", $mimeType)){
                        header('Content-Disposition: inline; filename="'.basename($data['oldName']).'"');
                    }else{
                        header('Content-Disposition: attachment; filename="'.basename($data['oldName']).'"');
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
                } else {
                    include($baseDir . 'templates/media.php');
                }
            } else {
                include($baseDir. 'templates/404.php');
            }
            break;
    }
} catch(Exception $e){
    include($baseDir . 'templates/500.php');
}
