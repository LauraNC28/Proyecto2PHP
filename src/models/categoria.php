<?php

/**
 * Clase Categoria
 * 
 * Modelo que gestiona las operaciones relacionadas con las categorías en la base de datos,
 * como crear, obtener, listar y eliminar categorías.
 */
class Categoria
{
    /** @var int|null $id ID de la categoría */
    private $id;

    /** @var string|null $nombre Nombre de la categoría */
    private $nombre;

    /** @var mysqli $db Conexión a la base de datos */
    private $db;

    /**
     * Constructor: Establece conexión con la base de datos.
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    // Getters

    /**
     * Devuelve el ID de la categoría.
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Devuelve el nombre de la categoría.
     * @return string|null
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    // Setters

    /**
     * Establece el ID de la categoría, solo si es numérico.
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = is_numeric($id) ? (int) $id : null;
    }

    /**
     * Establece y escapa el nombre de la categoría.
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $this->db->real_escape_string(trim($nombre));
    }

    // Métodos de operación

    /**
     * Obtiene todas las categorías de la base de datos.
     * @return mysqli_result|false
     */
    public function getAll()
    {
        $sql = "SELECT * FROM categorias";
        return $this->db->query($sql);
    }

    /**
     * Obtiene una única categoría por su ID.
     * @return object|null Objeto de categoría o null si no existe.
     */
    public function getOne()
    {
        if ($this->id === null) {
            return null;
        }

        $sql = "SELECT * FROM categorias WHERE id={$this->id} LIMIT 1";
        $categoria = $this->db->query($sql);
        return $categoria ? $categoria->fetch_object() : null;
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     * @return bool True si se insertó correctamente, false si hubo error.
     */
    public function save()
    {
        $sql = "INSERT INTO categorias (nombre) VALUES ('{$this->nombre}')";
        return $this->db->query($sql);
    }

    /**
     * Elimina una categoría si no tiene productos asociados.
     * 
     * @param int $id ID de la categoría a eliminar.
     * @return bool True si se eliminó correctamente, false si tiene productos asociados.
     */
    public function deleteOne($id)
    {
        // Verifica si existen productos asociados a esta categoría
        $sqlCheck = "SELECT COUNT(*) as total FROM productos WHERE categoria_id={$id}";
        $result = $this->db->query($sqlCheck);
        $row = $result->fetch_assoc();

        if ($row['total'] > 0) {
            // No se puede eliminar porque tiene productos
            return false;
        }

        // Elimina la categoría
        $sql = "DELETE FROM categorias WHERE id={$id}";
        return $this->db->query($sql);
    }
}