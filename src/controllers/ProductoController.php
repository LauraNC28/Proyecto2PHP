<?php

require_once __DIR__ . '/../models/producto.php';

class ProductoController
{
    // Muestra productos destacados en la página principal
    public function index()
    {
        $producto = new Producto();
        $productos = $producto->getRandom(6);
        require_once __DIR__ . '/../views/producto/destacados.php';
    }

    // Muestra la información de un producto específico
    public function ver()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $producto = new Producto();
            $producto->setId($id);
            $prod = $producto->getProduct();
        }
        require_once __DIR__ . '/../views/producto/ver.php';
    }

    // Muestra la gestión de productos (solo administradores)
    public function gestion()
    {
        Utils::isAdmin();
        $producto = new Producto();
        $productos = $producto->getAll();
        require_once __DIR__ . '/../views/producto/gestion.php';
    }

    // Muestra el formulario de creación de productos
    public function crear()
    {
        require_once __DIR__ . '/../views/producto/crear.php';
    }

    // Guarda o edita un producto en la base de datos
    public function save()
    {
        Utils::isAdmin();
        if (!empty($_POST)) {
            $nombre = $_POST['name'] ?? false;
            $descripcion = $_POST['description'] ?? false;
            $categoria = $_POST['category'] ?? false;
            $precio = $_POST['price'] ?? false;
            $stock = $_POST['stock'] ?? false;
            $oferta = $_POST['offer'] ?? false;

            if ($nombre && $descripcion && $categoria && $precio && $stock && $oferta) {
                $producto = new Producto();
                $producto->setNombre($nombre);
                $producto->setDescripcion($descripcion);
                $producto->setCategoria_id($categoria);
                $producto->setPrecio($precio);
                $producto->setStock($stock);
                $producto->setOferta($oferta);

                // Manejo de imagen
                if (!empty($_FILES['image']['name'])) {
                    $file = $_FILES['image'];
                    $filename = $file['name'];
                    $mimetype = $file['type'];

                    if (in_array($mimetype, ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'])) {
                        if (!is_dir('uploads/images')) {
                            mkdir('uploads/images', 0777, true);
                        }
                        move_uploaded_file($file['tmp_name'], 'uploads/images/' . $filename);
                        $producto->setImagen($filename);
                    }
                }

                if (isset($_GET['id'])) {
                    $producto->setId($_GET['id']);
                    $save = $producto->edit();
                } else {
                    $save = $producto->save();
                }

                $_SESSION['producto'] = $save ? 'completed' : 'failed';
            } else {
                $_SESSION['producto'] = 'failed';
            }
        } else {
            $_SESSION['producto'] = 'failed';
        }
        header('Location:' . base_url . 'producto/gestion');
    }

    // Muestra el formulario de edición de un producto
    public function editar()
    {
        Utils::isAdmin();
        if (isset($_GET['id'])) {
            $edit = true;
            $producto = new Producto();
            $producto->setId($_GET['id']);
            $prod = $producto->getProduct();
            require_once 'views/producto/crear.php';
        } else {
            header('Location:' . base_url . 'producto/gestion');
        }
    }

    // Elimina un producto de la base de datos
    public function eliminar()
    {
        Utils::isAdmin();
        if (isset($_GET['id'])) {
            $producto = new Producto();
            $producto->setId($_GET['id']);
            $delete = $producto->delete();
            $_SESSION['delete'] = $delete ? 'completed' : 'failed';
        } else {
            $_SESSION['delete'] = 'failed';
        }
        header('Location:' . base_url . 'producto/gestion');
    }
}
