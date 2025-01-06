<?php
spl_autoload_register(function ($class) {

    //define directories
    $paths = [
        __DIR__ . '/classes/',
        __DIR__ . '/config/',
    ];

    //returns the class file path with name
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
