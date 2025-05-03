<?php

require_once 'models/categoria.php';
require_once 'models/producto.php';

class CategoriaController
{
    // Muestra todas las categorías (requiere ser administrador)
    public function index()
    {
        Utils::isAdmin();
        $categoria = new Categoria();
        $categorias = $categoria->getAll();
        require_once 'views/categoria/index.php';
    }

    // Muestra una categoría específica y sus productos asociados
    public function ver()
    {
        if (!isset($_GET['id'])) {
            header('Location:' . base_url . 'categoria/index');
            exit();
        }

        // Obtener categoría
        $id = $_GET['id'];
        $categoria = new Categoria();
        $categoria->setId($id);
        $categoria = $categoria->getOne();

        // Obtener productos de la categoría
        $producto = new Producto();
        $producto->setCategoria_id($id);
        $productos = $producto->getAllCategory();

        require_once 'views/categoria/ver.php';
    }

    // Muestra el formulario para crear una nueva categoría (requiere ser administrador)
    public function crear()
    {
        Utils::isAdmin();
        require_once 'views/categoria/crear.php';
    }

    // Guarda una nueva categoría en la base de datos
    public function save()
    {
        Utils::isAdmin();
        
        if (!empty($_POST['name'])) {
            $categoria = new Categoria();
            $categoria->setNombre($_POST['name']);
            $categoria->save();
        }
        
        header('Location:' . base_url . 'categoria/index');
    }
}
