<?php

require_once __DIR__ . '/../models/pedido.php';
require_once __DIR__ . '/../models/producto.php';

/**
 * Clase PedidoController
 * 
 * Controlador encargado de gestionar todo el flujo relacionado con pedidos:
 * hacer pedidos, confirmarlos, ver historial, y su gestión por parte de administradores.
 */
class PedidoController
{
    /**
     * Muestra el formulario para que el usuario introduzca los datos de envío.
     *
     * @return void
     */
    public function hacer()
    {
        require_once __DIR__ . '/../views/pedido/hacer.php';
    }

    /**
     * Procesa y guarda un nuevo pedido en la base de datos junto con sus líneas de producto.
     * Solo disponible para usuarios registrados.
     *
     * @return void
     */
    public function add()
    {
        Utils::isLogged(); // Verifica que el usuario ha iniciado sesión
        $usuario_id = $_SESSION['identity']->id;

        $provincia = $_POST['provincia'] ?? false;
        $localidad = $_POST['localidad'] ?? false;
        $direccion = $_POST['direccion'] ?? false;
        $stats = Utils::statsCarrito(); // Obtiene total del carrito
        $coste = $stats['total'];

        if ($provincia && $localidad && $direccion) {
            $pedido = new Pedido();
            $pedido->setUsuario_id($usuario_id);
            $pedido->setProvincia($provincia);
            $pedido->setLocalidad($localidad);
            $pedido->setDireccion($direccion);
            $pedido->setCoste($coste);

            // Guarda el pedido y las líneas de producto
            $saved = $pedido->save();
            $save_linea = $pedido->saveLine();

            $_SESSION['pedido'] = ($saved && $save_linea) ? 'completed' : 'failed';
        } else {
            $_SESSION['pedido'] = 'failed';
        }

        header('Location:' . base_url . 'pedido/confirmado');
    }

    /**
     * Muestra la página de confirmación con el último pedido realizado por el usuario.
     *
     * @return void
     */
    public function confirmado()
    {
        if (isset($_SESSION['identity'])) {
            $identity = $_SESSION['identity'];

            $pedido = new Pedido();
            $pedido->setUsuario_id($identity->id);
            $pedido = $pedido->getOneByUser();

            $pedido_productos = new Pedido();
            $productos = $pedido_productos->getProductosByPedido($pedido->id);
        }

        require_once __DIR__ . '/../views/pedido/confirmado.php';
    }

    /**
     * Muestra todos los pedidos realizados por el usuario actual.
     *
     * @return void
     */
    public function mis_pedidos()
    {
        Utils::isLogged();
        $usuario_id = $_SESSION['identity']->id;

        $pedido = new Pedido();
        $pedido->setUsuario_id($usuario_id);
        $pedidos = $pedido->getByUser();

        require_once __DIR__ . '/../views/pedido/mis-pedidos.php';
    }

    /**
     * Muestra los detalles de un pedido específico.
     * El ID del pedido debe venir por la URL.
     *
     * @return void
     */
    public function detalle()
    {
        Utils::isLogged();

        if (!isset($_GET['id'])) {
            header('Location:' . base_url . 'pedido/mis_pedidos');
            exit();
        }

        $id = $_GET['id'];

        $pedido = new Pedido();
        $pedido->setId($id);
        $ped = $pedido->getOne();

        $productos_pedido = $pedido->getProductosByPedido($id);

        require_once __DIR__ . '/../views/pedido/detalle.php';
    }

    /**
     * Muestra todos los pedidos del sistema para el administrador.
     * Utiliza la misma vista que mis_pedidos pero en modo gestión.
     *
     * @return void
     */
    public function gestion()
    {
        Utils::isAdmin();
        $gestion = true; // Bandera para mostrar el modo administrador en la vista

        $pedido = new Pedido();
        $pedidos = $pedido->getAll();

        require_once __DIR__ . '/../views/pedido/mis-pedidos.php';
    }

    /**
     * Permite al administrador cambiar el estado de un pedido (en preparación, enviado, etc.).
     *
     * @return void
     */
    public function estado()
    {
        Utils::isAdmin();

        if (!empty($_POST['pedido_id']) && !empty($_POST['estado'])) {
            $pedido = new Pedido();
            $pedido->setId($_POST['pedido_id']);
            $pedido->setEstado($_POST['estado']);
            $pedido->updateOne();

            header('Location:' . base_url . 'pedido/detalle&id=' . $_POST['pedido_id']);
        } else {
            header('Location:' . index_url);
        }
    }
}