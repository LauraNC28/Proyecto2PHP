<?php

/**
 * Clase ErrorController
 *
 * Este controlador se encarga de gestionar los errores del sistema,
 * principalmente cuando el usuario accede a una ruta no válida o inexistente.
 */
class ErrorController {

    /**
     * Método por defecto (acción index).
     * 
     * Se ejecuta cuando se llama a un controlador o acción que no existe.
     * Muestra un mensaje simple de error al usuario.
     *
     * @return void
     */
    public function index() {
        echo '<h1>La página que buscas no existe</h1>';
    }
}