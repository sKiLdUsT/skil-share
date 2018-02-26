<?php

require(__DIR__ . '/../vendor/autoload.php');
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();
$handler = new Core\Session();
session_set_save_handler($handler, true);
session_start();

function displayError ($error = false) {
    ($error) || $error = error_get_last();
    if ( $error instanceof Exception || $error["type"] == E_ERROR || $error["type"] == E_PARSE )
    {
        $code = 500;
        http_response_code($code);
        $assets = json_decode(file_get_contents(__DIR__ . '/mix-manifest.json'), true);
        $assets = [
            'css' => $assets['/assets/css/app.css'],
            'manifest' => $assets['/assets/js/manifest.js'],
            'vendor' => $assets['/assets/js/vendor.js'],
        ];
        require(__DIR__ . '/../resources/views/error.php');
        exit(1);
    }
}

register_shutdown_function('displayError');

$router = new Core\Router();
$router->resolve();
