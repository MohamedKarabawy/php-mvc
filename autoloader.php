<?php

spl_autoload_register('autoloader');

function autoloader($class)
{
    $class_path = str_replace('\\', '/', $class);

    $file = __DIR__ . '/' . $class_path . '.php';

    if (file_exists($file)) {
        require_once $file;
    }

}
