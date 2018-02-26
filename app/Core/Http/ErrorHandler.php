<?php

namespace Core\Http;

use Core\Auth;

class ErrorHandler extends Handler {

    public function error(int $code)
    {
        header('Cache-Control: private; must-revalidate');
        http_response_code($code);
        $title = $code;
        $assets = json_decode(file_get_contents(__DIR__ . '/../../../public/mix-manifest.json'), true);
        $assets = [
            'css' => $assets['/assets/css/app.css'],
            'manifest' => $assets['/assets/js/manifest.js'],
            'vendor' => $assets['/assets/js/vendor.js'],
        ];
        require(__DIR__ . '/../../../resources/views/error.php');
    }
}