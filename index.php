<?php

include_once(__DIR__ . '/setup_project/setup_autoloader.php');

/* Автозагрузчик классов */
function myAutoload($class){
    //echo "<h1>$class</h1>";
    require_once($class.'.php');
}
spl_autoload_register('\myAutoload');

session_start();

/* Инициализация и запуск FrontController */
$router = Router::getInstance();