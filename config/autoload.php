<?php

class Autoload
{
    public static function start()
    {

        spl_autoload_register(function ($class) {
            $classPath = str_replace('\\', '/', $class) . '.php';
            if (file_exists($classPath)) {
                require_once $classPath;
            }
        });
        
        $root = $_SERVER['DOCUMENT_ROOT'];
        $host = $_SERVER['HTTP_HOST'];

        define("HOST", "http://" . $host . "/cinetech/");
        define('ROOT', $root . '/cinetech/');
        define("BASE_URL", "/cinetech/");

        define("CONTROLLER", ROOT . 'app/controllers/');
        define("MODEL", ROOT . 'app/models/');
        define("VIEW", ROOT . 'app/views/');
        define("CLASSES", ROOT . 'classes/');
        define("ASSETS", HOST . 'src/');
        define("config", ROOT . 'config/');

    }

    public static function autoload($class)
    {
        if (file_exists(CLASSES . $class . '.php')) {
            require_once CLASSES . $class . '.php';
        } elseif (file_exists(CONTROLLER . $class . '.php')) {
            require_once CONTROLLER . $class . '.php';
        } elseif (file_exists(MODEL . $class . '.php')) {
            require_once MODEL . $class . '.php';
        } elseif (file_exists(config . $class . '.php')) {
            require_once config . $class . '.php';
        } else {
            echo "La classe " . $class . " n'existe pas";
        }
    }
}