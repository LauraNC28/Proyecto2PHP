<?php

require_once __DIR__ . '/../models/producto.php';

/**
 * Clase ProductoController
 * 
 * Controlador responsable de gestionar todas las acciones relacionadas con productos,
 * como mostrar productos destacados, ver detalles, gestionar desde el panel de administración,
 * crear, editar y eliminar productos.
 */
class ProductoController
{
    /**
     * Muestra productos aleatorios (destacados) en la página de inicio.
     *
     * @return void
     */
    public function index()
    {
        $producto = new Producto();
        $productos = $producto->getRandom(6); // Obtener 6 productos aleatorios
        require_once __DIR__ . '/../views/producto/destacados.php';
    }

    /**
     * Muestra los detalles de un producto específico.
     * El ID del producto se pasa por la URL.
     *
     * @return void
     */
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

    /**
     * Muestra la lista de productos para administración.
     * Solo accesible si el usuario tiene rol de administrador.
     *
     * @return void
     */
    public function gestion()
    {
        Utils::isAdmin();
        $producto = new Producto();
        $productos = $producto->getAll();
        require_once __DIR__ . '/../views/producto/gestion.php';
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     *
     * @return void
     */
    public function crear()
    {
        require_once __DIR__ . '/../views/producto/crear.php';
    }

    /**
     * Guarda un nuevo producto o edita uno existente.
     * Verifica si los datos son válidos y maneja también la imagen del producto.
     * Requiere ser administrador.
     *
     * @return void
     */
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

            // Validar campos obligatorios
            if ($nombre && $descripcion && $categoria && $precio && $stock && $oferta) {
                $producto = new Producto();
                $producto->setNombre($nombre);
                $producto->setDescripcion($descripcion);
                $producto->setCategoria_id($categoria);
                $producto->setPrecio($precio);
                $producto->setStock($stock);
                $producto->setOferta($oferta);

                // Subida de imagen
                if (!empty($_FILES['image']['name'])) {
                    $file = $_FILES['image'];
                    $filename = $file['name'];
                    $mimetype = $file['type'];

                    // Validar tipo MIME permitido
                    if (in_array($mimetype, ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'])) {
                        // Crear carpeta si no existe
                        if (!is_dir('uploads/images')) {
                            mkdir('uploads/images', 0777, true);
                        }
                        move_uploaded_file($file['tmp_name'], 'uploads/images/' . $filename);
                        $producto->setImagen($filename);
                    }
                }

                // Guardar o actualizar
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

    /**
     * Muestra el formulario de edición de un producto ya existente.
     * Requiere el ID del producto en la URL.
     * Solo accesible para administradores.
     *
     * @return void
     */
    public function editar()
    {
        Utils::isAdmin();

        if (isset($_GET['id'])) {
            $edit = true;
            $producto = new Producto();
            $producto->setId($_GET['id']);
            $prod = $producto->getProduct();

            require_once __DIR__ . '/../views/producto/crear.php';
        } else {
            header('Location:' . base_url . 'producto/gestion');
        }
    }

    /**
     * Elimina un producto específico de la base de datos.
     * Solo accesible para administradores.
     *
     * @return void
     */
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