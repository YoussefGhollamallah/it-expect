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
        
    $root = $_SERVER['DOCUMENT_ROOT'] ?? getcwd();
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Détermine le chemin racine du projet quand exécuté en CLI
    $projectRoot = is_dir($root . '/cinetech') ? ($root . '/cinetech/') : (realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);

    if (!defined('HOST'))      define('HOST', "http://" . $host . "/cinetech/");
    if (!defined('ROOT'))      define('ROOT', $projectRoot);
    if (!defined('BASE_URL'))  define('BASE_URL', "/cinetech/");

    if (!defined('CONTROLLER')) define('CONTROLLER', ROOT . 'app/controllers/');
    if (!defined('MODEL'))      define('MODEL', ROOT . 'app/models/');
    if (!defined('VIEW'))       define('VIEW', ROOT . 'app/views/');
    if (!defined('CLASSES'))    define('CLASSES', ROOT . 'classes/');
    if (!defined('ASSETS'))     define('ASSETS', HOST . 'src/');
    if (!defined('config'))     define('config', ROOT . 'config/');

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