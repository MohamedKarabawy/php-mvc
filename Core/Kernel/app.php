<?php

namespace Core\Kernel;

use Core\Container;
use Core\Http\Request;

class App
{
    private static Container $container;

    public function __construct(string $routesPath)
    {
        // Load the application environment variables from the .env file
        \Core\env::load($routesPath . '/.env');

        // Load the framework helper functions
        require_once $routesPath . '/Core/helpers.php';

         // Create the application's dependency injection container
        self::$container = new Container();

        // Register the core framework services in the container
        $this->registerCoreServices();

        // Load and register the application's routes
        require_once $routesPath . '/app/Routes/routes.php';
    }

    private function registerCoreServices()
    {
        // Register the container itself as a singleton so it can be resolved through dependency injection
        self::$container->singleton(Container::class, function () 
        {
            return self::$container;
        });

        // Register the database connection as a singleton to reuse the same PDO instance
        self::$container->singleton(\Core\Database::class, function () 
        {
            return \Core\Database::connect();
        });

        // Register the current HTTP request as a singleton for the current application lifecycle
        self::$container->singleton(Request::class, function () 
        {
            return new Request();
        });
    }

    public static function getContainer(): Container
    {
        // Return the application's dependency injection container
        return self::$container;
    }
}
