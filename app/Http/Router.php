<?php

namespace App\Http;

use App\Http\Response\Response;

class Router
{
    const CONTROLLERS_NAMESPACE = '\App\Http\Controllers\\';

    public static $validRoutes = [];

    public static function set($requestMethod, $route, $controller, $method) {
        self::$validRoutes[$requestMethod][$route] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    public static function route(){
        $params = [];
        $urlPattern =[];

        if (!isset($_GET['url'])) {
            $requestUrl = '/';
        } else {
            $requestUrl = $_GET['url'];
        }

        $parts = explode('/', $requestUrl);

        foreach ($parts as $part) {
            if (is_numeric($part)) {
                $urlPattern[] = "{id}";
                $params[] = $part;
            } else {
                $urlPattern[] = $part;
            }
        }
        $urlPattern = implode("/", $urlPattern);

        foreach (self::$validRoutes as $requestMethod => $routeInfo) {
            foreach ($routeInfo as $path => $data) {

                if($path === $urlPattern && $_SERVER['REQUEST_METHOD'] === $requestMethod) {
                    $controller = $data['controller'];
                    $method = $data['method'];

                    $classNamespace = self::CONTROLLERS_NAMESPACE . $controller;
                    $instance = new $classNamespace();
                    $instance->$method(...$params);
                    return new Response();
                }

            }
        }
        self::redirect('/');
    }

    public static function redirect($location, $params = []) {
        $_SESSION['redirect_params'] = $params;
        header('Location: ' . $location);
        die();
    }
}
