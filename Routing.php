<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';

class Router
{

    public static $routes;

    public static function get($url, $controller)
    {
        self::$routes[$url] = $controller;
    }

    public static function post($url, $controller)
    {
        self::$routes[$url] = $controller;
    }

    public static function run($url)
    {
        $urlParts = explode("/", $url);
        $action = $urlParts[0] === '' ? 'login' : $urlParts[0];
        
        if (!array_key_exists($action, self::$routes)) {
            die("Wrong url!");
        }

        $controller = self::$routes[$action];
        $object = new $controller;

        if (!method_exists($object, $action)) {
            die("Action not found!");
        }

        $object->$action();
    }
}

Router::get('register', 'SecurityController');
Router::post('register', 'SecurityController');
Router::get('logout', 'SecurityController');