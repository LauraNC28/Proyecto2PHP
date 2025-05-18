<?php

class Utils
{
    public static function deleteSession($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
        return $name;
    }

    public static function showError($errors, $field)
    {
        $alert = '';
        if (is_array($errors) && isset($errors[$field]) && !empty($errors[$field])) {
            $alert = "<div class='alert error-alert'>" . htmlspecialchars($errors[$field]) . "</div>";
        }
        return $alert;
    }

    public static function isAdmin()
    {
        if (!isset($_SESSION['admin'])) {
            header('Location:' . base_url);
            exit();
        }
        return true;
    }

    public static function isLogged()
    {
        if (!isset($_SESSION['identity'])) {
            header('Location:' . base_url);
            exit();
        }
        return true;
    }

    public static function showCategorias()
    {
        require_once __DIR__ . '/../models/categoria.php';

        $categoria = new Categoria();
        return $categoria->getAll();
    }

    public static function showProductos()
    {
        require_once __DIR__ . '/../models/producto.php';

        $producto = new Producto();
        return $producto->getAll();
    }

    public static function statsCarrito()
    {
        $stats = [
            'count' => 0,
            'total' => 0
        ];

        if (!empty($_SESSION['carrito'])) {
            $stats['count'] = count($_SESSION['carrito']);

            foreach ($_SESSION['carrito'] as $producto) {
                $stats['total'] += $producto['precio'] * $producto['unidades'];
            }
        }

        return $stats;
    }

    public static function showEstado($status)
    {
        switch ($status) {
            case 'confirmed':
                return 'Pendiente';
            case 'preparation':
                return 'En preparación';
            case 'ready':
                return 'Preparado para enviar';
            default:
                return 'Enviado';
        }
    }
}
