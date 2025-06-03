<?php

ob_start();
// Inicia la sesión para el manejo de variables de usuario, login, carrito, etc.
session_start();

// Requiere los archivos esenciales para el funcionamiento del framework MVC
require_once 'config/db.php';              // Conexión a la base de datos
require_once 'autoload.php';              // Carga automática de controladores
require_once 'helpers/utils.php';         // Funciones auxiliares (validación, seguridad, etc.)
require 'config/parameters.php';          // Parámetros globales (base_url, controlador por defecto, etc.)
require 'views/layout/header.php';        // Cabecera HTML común en todas las vistas
require 'views/layout/sidebar.php';       // Barra lateral común (categorías, login, etc.)

// Conexión a la base de datos (opcional aquí si no se usa directamente)
$db = Database::connect();

/**
 * Muestra un mensaje de error genérico cuando no se encuentra el controlador o la acción.
 */
function show_Error() {
    $error = new ErrorController();
    $error->index(); // Carga vista de error
}

// -------------------------------
// Procesamiento de URL amigable
// -------------------------------

if (isset($_GET['url'])) {
    // Elimina barras iniciales/finales y separa la URL en partes
    $url = trim($_GET['url'], '/');
    $parts = explode('/', $url);

    // Primer segmento: nombre del controlador
    $controller = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : default_controller;

    // Segundo segmento (si existe): nombre del método o acción
    $action = $parts[1] ?? default_action;
} else {
    // Si no hay parámetros, carga controlador y acción por defecto
    $controller = default_controller;
    $action = default_action;
}

// -------------------------------
// Carga del controlador y acción
// -------------------------------

if (isset($controller) && class_exists($controller)) {
    $controlador = new $controller();

    if (isset($action) && method_exists($controlador, $action)) {
        // Ejecuta la acción solicitada
        $controlador->$action();
    } else {
        // La acción no existe en el controlador
        show_Error();
    }
} else {
    // El controlador no existe
    show_Error();
}

// Carga el pie de página común
require 'views/layout/footer.php';