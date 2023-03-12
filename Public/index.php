<?php

use Core\Router;


require '../vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

$router = new Router;

$router->add('', ['controller'=> 'home', 'action'=> 'index']);
$router->add('login', ['controller'=> 'login', 'action'=> 'index']);
$router->add('register', ['controller'=> 'register', 'action'=> 'index']);

$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');


$router->disPatch($_SERVER['QUERY_STRING']);


