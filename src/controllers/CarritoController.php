<?php

require_once __DIR__ . '/../models/producto.php';

/**
 * Clase CarritoController
 *
 * Controlador encargado de gestionar las operaciones del carrito de compras,
 * como agregar, eliminar, modificar cantidades y mostrar los productos.
 */
class CarritoController
{
    /**
     * Muestra el contenido actual del carrito de la sesión.
     *
     * @return void
     */
    public function index()
    {
        $carrito = $_SESSION['carrito'] ?? [];
        require_once __DIR__ . '/../views/carrito/index.php';
    }

    /**
     * Agrega un producto al carrito. Si ya existe, incrementa la cantidad.
     * También guarda el carrito en una cookie con duración de 30 días.
     *
     * @return void
     */
    public function add()
    {
        if (!isset($_GET['id'])) {
            header('Location:' . index_url);
            exit();
        }

        $producto_id = $_GET['id'];
        $encontrado = false;

        // Verificar si el producto ya está en el carrito
        if (!empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $indice => $elemento) {
                if ($elemento['id_producto'] == $producto_id) {
                    $_SESSION['carrito'][$indice]['unidades']++;
                    $encontrado = true;
                    break;
                }
            }
        }

        // Si no estaba en el carrito, se agrega como nuevo producto
        if (!$encontrado) {
            $producto = new Producto();
            $producto->setId($producto_id);
            $producto = $producto->getProduct();

            if ($producto) {
                $_SESSION['carrito'][] = [
                    "id_producto" => $producto->id,
                    "precio" => $producto->precio,
                    "unidades" => 1,
                    "producto" => $producto
                ];
            }
        }

        // Guardar el carrito en una cookie persistente (30 días)
        setcookie('carrito', json_encode($_SESSION['carrito']), time() + (30 * 86400), "/", "", false, true);

        header('Location:' . base_url . "carrito/index");
    }

    /**
     * Incrementa en uno la cantidad del producto en el carrito.
     *
     * @return void
     */
    public function up()
    {
        if (isset($_GET['index'])) {
            $_SESSION['carrito'][$_GET['index']]['unidades']++;

            // Guardar la sesión actualizada en la cookie
            setcookie('carrito', json_encode($_SESSION['carrito']), time() + (30 * 86400), "/", "", false, true);
        }

        header('Location:' . base_url . "carrito/index");
    }

    /**
     * Disminuye en uno la cantidad del producto en el carrito.
     * Si la cantidad llega a 0, se elimina del carrito.
     *
     * @return void
     */
    public function down()
    {
        if (isset($_GET['index'])) {
            $index = $_GET['index'];
            $_SESSION['carrito'][$index]['unidades']--;

            if ($_SESSION['carrito'][$index]['unidades'] <= 0) {
                unset($_SESSION['carrito'][$index]);
            }

            // Guardar la sesión actualizada en la cookie
            setcookie('carrito', json_encode($_SESSION['carrito']), time() + (30 * 86400), "/", "", false, true);
        }

        header('Location:' . base_url . "carrito/index");
    }

    /**
     * Elimina un producto del carrito completamente según su índice.
     *
     * @return void
     */
    public function remove()
    {
        if (isset($_GET['index'])) {
            unset($_SESSION['carrito'][$_GET['index']]);
            
            // Guardar la sesión actualizada en la cookie
            setcookie('carrito', json_encode($_SESSION['carrito']), time() + (30 * 86400), "/", "", false, true);
        }

        header('Location:' . base_url . "carrito/index");
    }

    /**
     * Vacía por completo el carrito de compras.
     * Elimina la variable de sesión y la cookie asociada.
     *
     * @return void
     */
    public function delete_all()
    {
        $_SESSION['carrito'] = [];

        // Eliminar cookie estableciendo tiempo en el pasado
        setcookie('carrito', '', time() - 3600, "/", "", false, true);

        header('Location:' . base_url . "carrito/index");
    }
}