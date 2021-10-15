<?php

spl_autoload_register(function ($className)
{
 $class = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $path = __DIR__ . '/' . $class . '.php';

    if (file_exists($path))
    {
        require_once $path;
    }
});
