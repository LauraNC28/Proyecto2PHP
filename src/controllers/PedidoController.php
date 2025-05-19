<?php

require_once __DIR__ . '/../models/pedido.php';
require_once __DIR__ . '/../models/producto.php';

class PedidoController
{
    // Muestra el formulario para hacer un pedido
    public function hacer()
    {
        require_once __DIR__ . '/../views/pedido/hacer.php';
    }

    // Agrega un pedido a la base de datos
    public function add()
    {
        Utils::isLogged();
        $usuario_id = $_SESSION['identity']->id;

        $provincia = $_POST['provincia'] ?? false;
        $localidad = $_POST['localidad'] ?? false;
        $direccion = $_POST['direccion'] ?? false;
        $stats = Utils::statsCarrito();
        $coste = $stats['total'];

        if ($provincia && $localidad && $direccion) {
            // Guardar datos del pedido en la base de datos
            $pedido = new Pedido();
            $pedido->setUsuario_id($usuario_id);
            $pedido->setProvincia($provincia);
            $pedido->setLocalidad($localidad);
            $pedido->setDireccion($direccion);
            $pedido->setCoste($coste);
            
            $saved = $pedido->save();
            $save_linea = $pedido->saveLine();

            $_SESSION['pedido'] = ($saved && $save_linea) ? 'completed' : 'failed';
        } else {
            $_SESSION['pedido'] = 'failed';
        }
        
        header('Location:' . base_url . 'pedido/confirmado');
    }

    // Muestra la confirmación del pedido
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

    // Muestra los pedidos del usuario actual
    public function mis_pedidos()
    {
        Utils::isLogged();
        $usuario_id = $_SESSION['identity']->id;
        
        $pedido = new Pedido();
        $pedido->setUsuario_id($usuario_id);
        $pedidos = $pedido->getByUser();
        
        require_once __DIR__ . '/../views/pedido/mis-pedidos.php';
    }

    // Muestra los detalles de un pedido específico
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

    // Gestión de pedidos para administradores
    public function gestion()
    {
        Utils::isAdmin();
        $gestion = true;
        
        $pedido = new Pedido();
        $pedidos = $pedido->getAll();
        
        require_once __DIR__ . '/../views/pedido/mis-pedidos.php';
    }

    // Actualiza el estado de un pedido
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
