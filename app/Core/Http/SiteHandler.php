<?php

namespace Core\Http;

use Core\Auth;
use DB\FileBag;

class SiteHandler extends Handler {

    public function index(\stdClass $request, string $flash_error = null)
    {
        header('Cache-Control: private; must-revalidate');
        $user = (object) ['username' => 'Anonymous'];
        if (Auth::check()) {
            $user = Auth::user();
        }
        $title = 'Home';
        $assets = json_decode(file_get_contents(__DIR__ . '/../../../public/mix-manifest.json'), true);
        $assets = [
            'css' => $assets['/assets/css/app.css'],
            'manifest' => $assets['/assets/js/manifest.js'],
            'vendor' => $assets['/assets/js/vendor.js'],
            'js' => $assets['/assets/js/index.js'],
        ];
        require(__DIR__ . '/../../../resources/views/index.php');
    }

    public function login(\stdClass $request, string $flash_error = null)
    {
        $_SESSION['token'] = bin2hex(random_bytes(32));
        header('Cache-Control: private; must-revalidate');
        $auth = Auth::check();
        if ($auth) {
            header('Location: /');
        }
        $title = 'Home';
        $assets = json_decode(file_get_contents(__DIR__ . '/../../../public/mix-manifest.json'), true);
        $assets = [
            'css' => $assets['/assets/css/app.css'],
            'manifest' => $assets['/assets/js/manifest.js'],
            'vendor' => $assets['/assets/js/vendor.js'],
        ];
        require(__DIR__ . '/../../../resources/views/auth/login.php');
    }

    public function register(\stdClass $request, string $flash_error = null)
    {
        $_SESSION['token'] = bin2hex(random_bytes(32));
        header('Cache-Control: private; must-revalidate');
        $auth = Auth::check();
        if ($auth) {
            header('Location: /');
        }
        $title = 'Home';
        $assets = json_decode(file_get_contents(__DIR__ . '/../../../public/mix-manifest.json'), true);
        $assets = [
            'css' => $assets['/assets/css/app.css'],
            'manifest' => $assets['/assets/js/manifest.js'],
            'vendor' => $assets['/assets/js/vendor.js'],
            'js' => $assets['/assets/js/register.js'],
        ];
        require(__DIR__ . '/../../../resources/views/auth/register.php');
    }

    public function upload(\stdClass $request, string $flash_error = null)
    {
        $_SESSION['token'] = bin2hex(random_bytes(32));
        header('Cache-Control: private; must-revalidate');
        $auth = Auth::check();
        if (!$auth) {
            header('Location: /');
        }
        $title = 'Upload';
        $assets = json_decode(file_get_contents(__DIR__ . '/../../../public/mix-manifest.json'), true);
        $assets = [
            'css' => $assets['/assets/css/app.css'],
            'manifest' => $assets['/assets/js/manifest.js'],
            'vendor' => $assets['/assets/js/vendor.js'],
            'js' => $assets['/assets/js/upload.js'],
        ];
        require(__DIR__ . '/../../../resources/views/upload.php');
    }

    public function files(\stdClass $request, string $flash_error = null)
    {
        header('Cache-Control: private; must-revalidate');
        $auth = Auth::check();
        if (!$auth) {
            header('Location: /');
        }
        $title = 'Files';
        $assets = json_decode(file_get_contents(__DIR__ . '/../../../public/mix-manifest.json'), true);
        $assets = [
            'css' => $assets['/assets/css/app.css'],
            'manifest' => $assets['/assets/js/manifest.js'],
            'vendor' => $assets['/assets/js/vendor.js'],
            'js' => $assets['/assets/js/files.js']
        ];
        $files = new FileBag(['uid', Auth::user()->id]);
        require(__DIR__ . '/../../../resources/views/files.php');
    }
}
