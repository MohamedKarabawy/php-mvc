<?php

namespace Core\Router;

use Core\Kernel\App;
use Core\Http\Request;

class Route
{

    public function __construct()
    {
    }

    public static function get(string $route, array|callable $callback)
    {
        $container = App::getContainer();
        $request = $container->get(Request::class);

        if ($request->getMethod() !== 'GET') 
        {
            return;
        }

        // Extract only the path component from the request URI (ignoring query strings, etc.)
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Trim slashes and split the path into segments using explode
        // Grab the first segment to establish the base path context
        $basePath = '/' . explode('/', trim($requestPath, '/'))[0];

        $routePath = $basePath . '/' . trim($route, '/');

        // Compare the URI path to the intended path
        if ($requestPath === $routePath) 
        {
            // Check if $callback is callable and not an array
            if(is_callable($callback) && !is_array($callback))
            {
                echo $callback();
                exit;
            }

            // Get the controller and method
            [$controller, $method] = $callback;

            // Instantiate the controller
            $controllerInstance = $container->get($controller);

            // Execute the controller method and output its response
            echo $controllerInstance->$method();

            exit;
        }
    }

    public static function post(string $route, array|callable $callback)
    {
        $container = App::getContainer();
        $request = $container->get(Request::class);

        if ($request->getMethod() !== 'POST') 
        {
            return;
        }

        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = '/' . explode('/', trim($requestPath, '/'))[0];

        $routePath = $basePath . '/' . trim($route, '/');

        if ($requestPath === $routePath) 
        {
            if(is_callable($callback) && !is_array($callback))
            {
                echo $callback();
                exit;
            }

            [$controller, $method] = $callback;

            $controllerInstance = $container->get($controller);

            echo $controllerInstance->$method();

            exit;
        }
    }
}
