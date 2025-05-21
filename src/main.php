<?php

session_start();
require_once 'config/db.php';
require_once 'autoload.php';
require_once 'helpers/utils.php';
require 'config/parameters.php';
require 'views/layout/header.php';
require 'views/layout/sidebar.php';

// Conexion a la base de datos

$db = Database::connect();

function show_Error(){
    $error = new ErrorController();
    $error->index();
}

// Procesar URL amigable
if (isset($_GET['url'])) {
    $url = trim($_GET['url'], '/');
    $parts = explode('/', $url);

    $controller = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : default_controller;
    $action = $parts[1] ?? default_action;
} else {
    $controller = default_controller;
    $action = default_action;
}


if (isset($controller) && class_exists($controller)) {
    $controlador = new $controller();

    if (isset($action) && method_exists($controlador, $action)) {
        $controlador->$action();
    } else {
        show_Error();
    }
} else {
    show_Error();
}

require 'views/layout/footer.php';
