<?php

class Categoria
{
    private $id;
    private $nombre;
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    // Setters con validaciones
    public function setId($id)
    {
        $this->id = is_numeric($id) ? (int) $id : null;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $this->db->real_escape_string(trim($nombre));
    }

    // Obtener todas las categorías
    public function getAll()
    {
        $sql = "SELECT * FROM categorias";
        return $this->db->query($sql);
    }

    // Obtener una categoría por ID
    public function getOne()
    {
        if ($this->id === null) {
            return null;
        }

        $sql = "SELECT * FROM categorias WHERE id={$this->id} LIMIT 1";
        $categoria = $this->db->query($sql);
        return $categoria ? $categoria->fetch_object() : null;
    }

    // Guardar una nueva categoría
    public function save()
    {
        $sql = "INSERT INTO categorias (nombre) VALUES ('{$this->nombre}')";
        return $this->db->query($sql);
    }

    
    public function deleteOne($id){
        $sqlCheck = "SELECT COUNT(*) as total FROM productos WHERE categoria_id={$id}";
        $result = $this->db->query($sqlCheck);
        $row = $result->fetch_assoc();

        if ($row['total'] > 0) {
            // Hay productos asociados, no eliminar
            return false;
        }

        // No hay productos asociados, eliminar la categoría
        $sql = "DELETE FROM categorias WHERE id={$id}";
        return $this->db->query($sql);
    }
}
