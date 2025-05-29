<?php

class Usuario {
    private $id;
    private $nombre;
    private $apellidos;
    private $email;
    private $password;
    private $rol;
    private $imagen;
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Métodos GETTERS
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRol() { return $this->rol; }
    public function getImagen() { return $this->imagen; }

    // Métodos SETTERS
    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $this->db->real_escape_string($nombre); }
    public function setApellidos($apellidos) { $this->apellidos = $this->db->real_escape_string($apellidos); }
    public function setEmail($email) { $this->email = $this->db->real_escape_string($email); }
    public function setPassword($password) { $this->password = $this->db->real_escape_string($password); }

    // Aquí se encripta la contraseña al establecerla
    public function hashPassword($password) { 
        $this->password = password_hash($this->db->real_escape_string($password), PASSWORD_BCRYPT, ['cost' => 4]); 
    }

    public function setRol($rol) { $this->rol = $this->db->real_escape_string($rol); }
    public function setImagen($imagen) { $this->imagen = $this->db->real_escape_string($imagen); }

    // Guardar usuario en la base de datos
    public function save() {
        $sql = "INSERT INTO usuarios VALUES (
            NULL, 
            '{$this->getNombre()}', 
            '{$this->getApellidos()}', 
            '{$this->getEmail()}', 
            '{$this->getPassword()}', 
            '{$this->getRol()}', 
            NULL
        )";
        
        $save = $this->db->query($sql);

        return $save ? true : false;
    }

    // Iniciar sesión verificando email y contraseña
    public function login() {
        $resultado = [];
        $email = $this->email;
        $password = $this->password;

        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $login = $this->db->prepare($sql);
        $login->bind_param("s", $email);
        $login->execute();
        $result = $login->get_result();
    
        if ($result && $result->num_rows == 1) {
            $usuario = $result->fetch_object();
            // Verificar la contraseña
            if (password_verify($password, $usuario->password)) {
                $resultado = $usuario;
            }
        }
        
        return $resultado;
    }
}
