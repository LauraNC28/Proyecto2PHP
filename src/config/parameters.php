<?php

/**
 * Archivo de configuración general del proyecto.
 * Define constantes que se utilizan en todo el sitio para facilitar su mantenimiento y reutilización.
 */

// URL base de la aplicación (se utiliza para generar rutas absolutas en enlaces, formularios, etc.)
define("base_url", "http://localhost/DesarrolloEntornoServidor/proyectoPhpMvc-main/proyectoPhpMvc-main/src/");

/**
 * URL que apunta al índice principal de la aplicación.
 * Se usa para redireccionar después de acciones como login o logout.
 * Normalmente apunta al archivo `index.php` en la raíz del proyecto.
 */
define("index_url", "http://localhost/DesarrolloEntornoServidor/proyectoPhpMvc-main/proyectoPhpMvc-main/");

/**
 * Controlador predeterminado del sistema.
 * Si no se especifica un controlador en la URL, se usará este por defecto.
 */
define("default_controller", "ProductoController");

/**
 * Acción predeterminada dentro del controlador.
 * Si no se especifica una acción en la URL, se ejecutará esta.
 */
define("default_action", "index");