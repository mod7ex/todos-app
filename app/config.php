<?php


define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);

define('APP_PATH', realpath(__DIR__) . DS);
define('PUBLIC_PATH', realpath(dirname(__DIR__)) . DS . 'public');
define('VIEWS_PATH', APP_PATH . 'views' . DS);

define('SESSION_SAVE_PATH', APP_PATH . 'sessions');
define('APP_HOST_NAME', '.' . $_SERVER['HTTP_HOST']);

define('APP_KEY', 'e338108a9928aa960b84ae0345a3bc79a03e8ad87209b9faf5bfa');



#----------- Db --------------

define('DB_HOST', 'localhost');
define('DB_NAME', 'todosdb');
define('DB_USER', 'root');
define('DB_PASS', 'password');

define('DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME);

#----------- Db --------------