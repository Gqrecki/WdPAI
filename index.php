<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('admin', 'DefaultController');
Router::get('drink', 'DefaultController');
Router::get('home', 'DefaultController');
Router::get('login', 'DefaultController');
Router::post('login', 'SecurityController');
Router::get('search', 'DefaultController');
Router::get('user', 'DefaultController');

Router::run($path);