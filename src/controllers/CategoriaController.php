<?php

// Incluye los modelos necesarios para manejar categorías y productos
require_once __DIR__ . '/../models/categoria.php';
require_once __DIR__ . '/../models/producto.php';

/**
 * Clase CategoriaController
 * 
 * Controlador encargado de gestionar las acciones relacionadas con las categorías.
 * Solo los administradores tienen acceso a la mayoría de sus métodos.
 */
class CategoriaController
{
    /**
     * Muestra el listado de todas las categorías.
     * Requiere que el usuario sea administrador.
     *
     * @return void
     */
    public function index() {
        Utils::isAdmin(); // Solo admins pueden acceder

        $categoria = new Categoria();
        $categorias = $categoria->getAll();

        // Carga la vista con la lista de categorías
        require_once __DIR__ . '/../views/categoria/index.php';
    }

    /**
     * Muestra una categoría específica y sus productos asociados.
     * Redirige si no se proporciona un ID.
     *
     * @return void
     */
    public function ver() {
        // Comprobar si se ha pasado un ID por la URL
        if (!isset($_GET['id'])) {
            header('Location:' . base_url . 'categoria/index');
            exit();
        }

        $id = $_GET['id'];

        // Obtener la categoría seleccionada
        $categoria = new Categoria();
        $categoria->setId($id);
        $categoria = $categoria->getOne();

        // Obtener los productos que pertenecen a esa categoría
        $producto = new Producto();
        $producto->setCategoria_id($id);
        $productos = $producto->getAllCategory();

        // Cargar la vista
        require_once __DIR__ . '/../views/categoria/ver.php';
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     * Solo accesible para administradores.
     *
     * @return void
     */
    public function crear()
    {
        Utils::isAdmin();
        require_once __DIR__ . '/../views/categoria/crear.php';
    }

    /**
     * Elimina una categoría si no tiene productos asociados.
     * Solo accesible para administradores.
     * 
     * @return void
     */
    public function eliminar() {
        Utils::isAdmin();

        // Comprobar si se ha pasado un ID
        if (!isset($_GET['id'])) {
            header('Location:' . base_url . 'categoria/index');
            exit();
        }

        $id = $_GET['id'];
        $categoria = new Categoria();

        // Intenta eliminar
        $eliminado = $categoria->deleteOne($id);

        // Guardar mensaje de éxito o error en la sesión
        if ($eliminado) {
            $_SESSION['categoria_eliminada'] = "Categoría eliminada correctamente";
        } else {
            $_SESSION['categoria_eliminada'] = "No se puede eliminar la categoría porque tiene productos asociados";
        }

        header('Location:' . base_url . 'categoria/index');
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     * Solo accesible para administradores.
     *
     * @return void
     */
    public function save() {
        Utils::isAdmin();

        if (!empty($_POST['name'])) {
            $categoria = new Categoria();
            $categoria->setNombre($_POST['name']);
            $categoria->save();
        }

        header('Location:' . base_url . 'categoria/index');
    }
}