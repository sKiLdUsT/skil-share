<?php

namespace Core;

class Router
{
    protected $request;
    public function __construct()
    {
        $this->request = (object)['url' => null, 'type' => null, 'arguments' => null];
        $this->request->url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->request->type = 'GET';
                $this->request->input = (object) $_GET;
                break;
            case 'POST':
                $this->request->type = 'POST';
                $this->request->input = (object) $_POST;
                break;
            default:
                $this->request->type = 'UNDEFINED';
                break;
        }
    }

    public function resolve()
    {
        if($this->request->type !== 'UNDEFINED')
        {
            $routes = require(__DIR__ . '/../../resources/routes.php');
            if (isset($routes[$this->request->type][$this->request->url]))
            {
                try {
                    $caller = explode('@', $routes[$this->request->type][$this->request->url]);
                    $caller[0] = "Core\Http\\". $caller[0];
                    $handler = new $caller[0]();
                    if ($handler instanceof Http\Handler) $handler->{$caller[1]}($this->request);
                    else {
                        $handler = new Http\ErrorHandler();
                        $handler->error(500);
                    }
                } catch (\Exception $e) {
                    $handler = new Http\ErrorHandler();
                    $handler->error(500);
                }
            } else if (preg_match('/^\/[0-9a-fA-F]{6,8}$/', $this->request->url)) {
                $handler = new Http\FileHandler();
                $handler->display($this->request);
            } else {
                $handler = new Http\ErrorHandler();
                $handler->error(404);
            }
        }
    }
}

