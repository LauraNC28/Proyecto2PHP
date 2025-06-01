<?php

/**
 * Clase Pedido
 * 
 * Modelo encargado de gestionar los pedidos en el sistema:
 * guardar, consultar, actualizar estado y asociar productos.
 */
class Pedido
{
    /** @var int|null ID del pedido */
    private $id;

    /** @var int|null ID del usuario que hizo el pedido */
    private $usuario_id;

    /** @var string|null Provincia del usuario */
    private $provincia;

    /** @var string|null Localidad del usuario */
    private $localidad;

    /** @var string|null Dirección de envío */
    private $direccion;

    /** @var float|null Coste total del pedido */
    private $coste;

    /** @var string|null Estado del pedido (confirmed, preparation, ready, sent) */
    private $estado;

    /** @var string|null Fecha del pedido */
    private $fecha;

    /** @var string|null Hora del pedido */
    private $hora;

    /** @var mysqli Conexión a la base de datos */
    private $db;

    /**
     * Constructor. Establece la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    // ───── GETTERS Y SETTERS ─────────────────────

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getUsuario_id() { return $this->usuario_id; }
    public function setUsuario_id($usuario_id) { $this->usuario_id = $usuario_id; }

    public function getProvincia() { return $this->provincia; }
    public function setProvincia($provincia) { $this->provincia = $this->db->real_escape_string($provincia); }

    public function getLocalidad() { return $this->localidad; }
    public function setLocalidad($localidad) { $this->localidad = $this->db->real_escape_string($localidad); }

    public function getDireccion() { return $this->direccion; }
    public function setDireccion($direccion) { $this->direccion = $this->db->real_escape_string($direccion); }

    public function getCoste() { return $this->coste; }
    public function setCoste($coste) { $this->coste = $coste; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }

    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; }

    public function getHora() { return $this->hora; }
    public function setHora($hora) { $this->hora = $hora; }

    // ───── MÉTODOS FUNCIONALES ─────────────────────

    /**
     * Obtiene todos los pedidos, ordenados por ID descendente.
     * @return mysqli_result
     */
    public function getAll()
    {
        $sql = "SELECT * FROM pedidos ORDER BY id DESC";
        return $this->db->query($sql);
    }

    /**
     * Obtiene un único pedido según su ID.
     * @return object|null Pedido encontrado o null.
     */
    public function getOne()
    {
        $sql = "SELECT * FROM pedidos WHERE id={$this->getId()} ORDER BY id DESC";
        $result = $this->db->query($sql);
        return $result->fetch_object();
    }

    /**
     * Obtiene el último pedido realizado por un usuario.
     * @return object|null Pedido encontrado o null.
     */
    public function getOneByUser()
    {
        $sql = "SELECT p.id, p.coste FROM pedidos p 
                WHERE p.usuario_id={$this->getUsuario_id()} 
                ORDER BY id DESC LIMIT 1";
        $result = $this->db->query($sql);
        return $result->fetch_object();
    }

    /**
     * Obtiene todos los pedidos realizados por un usuario.
     * @return mysqli_result
     */
    public function getByUser()
    {
        $sql = "SELECT p.* FROM pedidos p 
                WHERE p.usuario_id={$this->getUsuario_id()} 
                ORDER BY id DESC";
        return $this->db->query($sql);
    }

    /**
     * Obtiene todos los productos asociados a un pedido concreto.
     *
     * @param int $id ID del pedido
     * @return mysqli_result Lista de productos con unidades.
     */
    public function getProductosByPedido($id)
    {
        $sql = "SELECT pr.*, lp.unidades 
                FROM productos pr 
                INNER JOIN lineas_pedidos lp 
                ON pr.id = lp.producto_id 
                WHERE lp.pedido_id = {$id}";
        return $this->db->query($sql);
    }

    /**
     * Guarda el pedido principal (cabecera) en la base de datos.
     * @return bool True si se insertó correctamente.
     */
    public function save()
    {
        $sql = "INSERT INTO pedidos 
                VALUES (null, {$this->usuario_id}, '{$this->provincia}', 
                        '{$this->localidad}', '{$this->direccion}', 
                        {$this->coste}, 'confirmed', CURDATE(), CURTIME());";
        return $this->db->query($sql);
    }

    /**
     * Guarda las líneas de pedido (productos comprados).
     * Utiliza el último ID insertado (pedido actual).
     * @return bool True si se guardaron correctamente las líneas.
     */
    public function saveLine()
    {
        $query = $this->db->query("SELECT LAST_INSERT_ID() as 'pedido';");
        $pedido_id = $query->fetch_object()->pedido;

        foreach ($_SESSION['carrito'] as $elemento) {
            $producto = $elemento['producto'];
            $unidades = $elemento['unidades'];

            $insert = "INSERT INTO lineas_pedidos 
                       VALUES(NULL, {$pedido_id}, {$producto->id}, {$unidades});";
            $save = $this->db->query($insert);

            // Actualizar stock tras registrar línea
            $this->updateStock($producto->id, $unidades);
        }

        return isset($save) && $save;
    }

    /**
     * Actualiza el estado de un pedido (por ejemplo: enviado, preparado, etc).
     * @return bool True si se actualizó correctamente.
     */
    public function updateOne()
    {
        $sql = "UPDATE pedidos SET estado = '{$this->getEstado()}' WHERE id={$this->getId()};";
        return $this->db->query($sql);
    }

    /**
     * Actualiza el stock de un producto al realizar un pedido.
     *
     * @param int $id ID del producto
     * @param int $unidades Cantidad vendida a descontar
     * @return void
     */
    public function updateStock($id, $unidades)
    {
        $query = $this->db->query("SELECT stock FROM productos WHERE id= $id");
        $stock = $query->fetch_object()->stock;

        $newStock = max(0, $stock - $unidades); // evita valores negativos

        $sql = "UPDATE productos SET stock = $newStock WHERE id = $id";
        $this->db->query($sql);
    }
}