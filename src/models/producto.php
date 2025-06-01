<?php

/**
 * Clase Producto
 *
 * Modelo que gestiona las operaciones relacionadas con productos
 * en la base de datos: crear, leer, actualizar, eliminar, y listar.
 */
class Producto
{
    /** @var int|null ID del producto */
    private $id;

    /** @var int|null ID de la categoría a la que pertenece el producto */
    private $categoria_id;

    /** @var string|null Nombre del producto */
    private $nombre;

    /** @var string|null Descripción del producto */
    private $descripcion;

    /** @var float Precio del producto */
    private $precio;

    /** @var int Stock disponible */
    private $stock;

    /** @var string|null Oferta o promoción del producto */
    private $oferta;

    /** @var string|null Ruta o nombre de la imagen del producto */
    private $imagen;

    /** @var string|null Fecha de creación del producto */
    private $fecha;

    /** @var mysqli Conexión a la base de datos */
    private $db;

    /**
     * Constructor que establece la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    // ───────────────────── GETTERS Y SETTERS ─────────────────────

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = is_numeric($id) ? (int) $id : null; }

    public function getCategoria_id() { return $this->categoria_id; }
    public function setCategoria_id($categoria_id) { $this->categoria_id = is_numeric($categoria_id) ? (int) $categoria_id : null; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $this->db->real_escape_string(trim($nombre)); }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $this->db->real_escape_string(trim($descripcion)); }

    public function getPrecio() { return $this->precio; }
    public function setPrecio($precio) { $this->precio = is_numeric($precio) ? (float) $precio : 0.0; }

    public function getStock() { return $this->stock; }
    public function setStock($stock) { $this->stock = is_numeric($stock) ? (int) $stock : 0; }

    public function getOferta() { return $this->oferta; }
    public function setOferta($oferta) { $this->oferta = $this->db->real_escape_string(trim($oferta)); }

    public function getImagen() { return $this->imagen; }
    public function setImagen($imagen) { $this->imagen = $this->db->real_escape_string($imagen); }

    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; }

    // ───────────────────── MÉTODOS DE OPERACIÓN ─────────────────────

    /**
     * Obtiene todos los productos de la base de datos.
     * @return mysqli_result
     */
    public function getAll()
    {
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        return $this->db->query($sql);
    }

    /**
     * Obtiene todos los productos de una categoría específica.
     * @return mysqli_result|false Lista de productos o false si no hay categoría válida.
     */
    public function getAllCategory()
    {
        if ($this->categoria_id === null) {
            return false;
        }

        $sql = "SELECT p.*, c.nombre AS nombre_cat 
                FROM productos p 
                INNER JOIN categorias c ON c.id = p.categoria_id 
                WHERE p.categoria_id = {$this->categoria_id} 
                ORDER BY id DESC";

        return $this->db->query($sql);
    }

    /**
     * Devuelve un número aleatorio de productos.
     *
     * @param int $limit Número de productos a devolver.
     * @return mysqli_result
     */
    public function getRandom($limit)
    {
        $limit = is_numeric($limit) ? (int) $limit : 6;
        $sql = "SELECT * FROM productos ORDER BY RAND() LIMIT $limit";
        return $this->db->query($sql);
    }

    /**
     * Obtiene un producto específico por su ID.
     * @return object|null Objeto del producto o null si no se encuentra.
     */
    public function getProduct()
    {
        if ($this->id === null) {
            return null;
        }

        $sql = "SELECT * FROM productos WHERE id={$this->id} LIMIT 1";
        $producto = $this->db->query($sql);
        return $producto ? $producto->fetch_object() : null;
    }

    /**
     * Guarda un nuevo producto en la base de datos.
     * @return bool True si se insertó correctamente.
     */
    public function save()
    {
        $sql = "INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, oferta, fecha, imagen) 
                VALUES ({$this->categoria_id}, '{$this->nombre}', '{$this->descripcion}', '{$this->precio}', 
                        {$this->stock}, '{$this->oferta}', CURDATE(), '{$this->imagen}')";
        return $this->db->query($sql);
    }

    /**
     * Edita un producto existente.
     * @return bool True si la edición fue exitosa.
     */
    public function edit()
    {
        if ($this->id === null) {
            return false;
        }

        $sql = "UPDATE productos SET 
                    categoria_id = '{$this->categoria_id}', 
                    nombre = '{$this->nombre}', 
                    descripcion = '{$this->descripcion}', 
                    precio = '{$this->precio}', 
                    stock = {$this->stock}, 
                    oferta = '{$this->oferta}'";

        if ($this->imagen !== null) {
            $sql .= ", imagen='{$this->imagen}'";
        }

        $sql .= " WHERE id={$this->id}";

        return $this->db->query($sql);
    }

    /**
     * Elimina un producto por su ID.
     * @return bool True si se eliminó correctamente.
     */
    public function delete()
    {
        if ($this->id === null) {
            return false;
        }

        $sql = "DELETE FROM productos WHERE id={$this->id}";
        return $this->db->query($sql);
    }
}