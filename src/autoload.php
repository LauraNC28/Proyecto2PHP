<?php

/**
 * Función de autocarga personalizada para los controladores.
 *
 * Esta función se encarga de incluir automáticamente los archivos
 * de los controladores ubicados en la carpeta `controllers/`
 * siempre que se instancie una clase que no ha sido incluida aún.
 *
 * @param string $classname Nombre de la clase a cargar automáticamente.
 *                          Se convierte a mayúscula inicial para coincidir con el nombre del archivo.
 *                          Por ejemplo: productoController → ProductoController.php
 */
function controllers_autoload($classname) {
    // Asegura que la primera letra del nombre de la clase esté en mayúscula
    $classname = ucfirst($classname);

    // Incluye el archivo correspondiente desde la carpeta de controladores
    include 'controllers/' . $classname . '.php';
}

// Registra la función de autocarga para que se ejecute automáticamente
// cuando se intente instanciar una clase no definida
spl_autoload_register('controllers_autoload');