<?php

/**
 * Clase Utils
 *
 * Clase de utilidades con métodos estáticos reutilizables para gestión
 * de sesiones, control de acceso, estadísticas del carrito, y otras funciones comunes.
 */
class Utils
{
    /**
     * Elimina una variable de sesión si existe.
     *
     * @param string $name Nombre de la variable de sesión a eliminar.
     * @return string Retorna el nombre de la sesión eliminada.
     */
    public static function deleteSession($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
        return $name;
    }

    /**
     * Muestra un mensaje de error asociado a un campo del formulario.
     *
     * @param array $errors Array con errores de validación.
     * @param string $field Nombre del campo a validar.
     * @return string HTML del mensaje de error.
     */
    public static function showError($errors, $field)
    {
        $alert = '';
        if (is_array($errors) && isset($errors[$field]) && !empty($errors[$field])) {
            $alert = "<div class='alert error-alert'>" . htmlspecialchars($errors[$field]) . "</div>";
        }
        return $alert;
    }

    /**
     * Verifica si el usuario tiene privilegios de administrador.
     * Si no es admin, redirige a la página de inicio.
     *
     * @return bool True si es admin.
     */
    public static function isAdmin()
    {
        if (!isset($_SESSION['admin'])) {
            header('Location:' . index_url);
            exit();
        }
        return true;
    }

    /**
     * Verifica si el usuario está autenticado (logueado).
     * Si no lo está, redirige a la página de inicio.
     *
     * @return bool True si está logueado.
     */
    public static function isLogged()
    {
        if (!isset($_SESSION['identity'])) {
            header('Location:' . index_url);
            exit();
        }
        return true;
    }

    /**
     * Devuelve todas las categorías disponibles desde el modelo.
     *
     * @return mysqli_result Resultado de la consulta de categorías.
     */
    public static function showCategorias()
    {
        require_once __DIR__ . '/../models/categoria.php';

        $categoria = new Categoria();
        return $categoria->getAll();
    }

    /**
     * Devuelve todos los productos disponibles desde el modelo.
     *
     * @return mysqli_result Resultado de la consulta de productos.
     */
    public static function showProductos()
    {
        require_once __DIR__ . '/../models/producto.php';

        $producto = new Producto();
        return $producto->getAll();
    }

    /**
     * Calcula el total de productos y el importe total del carrito.
     *
     * @return array Array con 'count' (cantidad de productos) y 'total' (importe total).
     */
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

    /**
     * Devuelve una descripción legible del estado de un pedido.
     *
     * @param string $status Estado interno (confirmed, preparation, ready, sent).
     * @return string Estado en formato amigable para el usuario.
     */
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