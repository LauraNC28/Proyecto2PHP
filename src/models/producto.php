<?php

class Producto
{
    private $id;
    private $categoria_id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $stock;
    private $oferta;
    private $imagen;
    private $fecha;
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // Getters y Setters con validaciones
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

    // Obtener todos los productos
    public function getAll()
    {
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        return $this->db->query($sql);
    }

    // Obtener productos por categoría
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

    // Obtener productos aleatorios
    public function getRandom($limit)
    {
        $limit = is_numeric($limit) ? (int) $limit : 6;
        $sql = "SELECT * FROM productos ORDER BY RAND() LIMIT $limit";
        return $this->db->query($sql);
    }

    // Obtener un producto por ID
    public function getProduct()
    {
        if ($this->id === null) {
            return null;
        }

        $sql = "SELECT * FROM productos WHERE id={$this->id} LIMIT 1";
        $producto = $this->db->query($sql);
        return $producto ? $producto->fetch_object() : null;
    }

    // Guardar un nuevo producto
    public function save()
    {
        $sql = "INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, oferta, fecha, imagen) 
                VALUES ({$this->categoria_id}, '{$this->nombre}', '{$this->descripcion}', '{$this->precio}', 
                        {$this->stock}, '{$this->oferta}', CURDATE(), '{$this->imagen}')";
        return $this->db->query($sql);
    }

    // Editar un producto existente
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

    // Eliminar un producto
    public function delete()
    {
        if ($this->id === null) {
            return false;
        }

        $sql = "DELETE FROM productos WHERE id={$this->id}";
        return $this->db->query($sql);
    }
}

