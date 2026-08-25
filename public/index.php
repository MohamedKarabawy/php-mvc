<?php

// Load the framework autoloader to automatically resolve application and framework classes
require_once dirname(__DIR__) . '/autoloader.php';

use Core\Kernel\App;

// Define the application's root directory for resolving framework and application resources
define('BASE_PATH', dirname(__DIR__));

// Create and initialize the application using the application's root path
$app = new App(BASE_PATH);


