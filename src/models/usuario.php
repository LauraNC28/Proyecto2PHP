<?php

/**
 * Clase Usuario
 *
 * Modelo que representa a un usuario del sistema y gestiona su información en la base de datos.
 * Permite registrar, iniciar sesión, verificar el correo electrónico y obtener/modificar datos.
 */
class Usuario {
    /** @var int|null ID del usuario */
    private $id;

    /** @var string|null Nombre del usuario */
    private $nombre;

    /** @var string|null Apellidos del usuario */
    private $apellidos;

    /** @var string|null Correo electrónico del usuario */
    private $email;

    /** @var string|null Contraseña del usuario (encriptada) */
    private $password;

    /** @var string|null Rol del usuario (admin, user, etc.) */
    private $rol;

    /** @var string|null Imagen de perfil del usuario */
    private $imagen;

    /** @var int 1 si el correo ha sido verificado, 0 si no */
    private $email_verificado;

    /** @var string|null Token de verificación de email */
    private $token_verificacion;

    /** @var mysqli Conexión a la base de datos */
    private $db;

    /**
     * Constructor. Establece la conexión con la base de datos.
     */
    public function __construct() {
        $this->db = Database::connect();
    }

    // ───────────── GETTERS ─────────────

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRol() { return $this->rol; }
    public function getImagen() { return $this->imagen; }
    public function getEmailVerificado() { return $this->email_verificado; }
    public function getTokenVerificacion() { return $this->token_verificacion; }

    // ───────────── SETTERS ─────────────

    public function setId($id) { $this->id = $id; }

    public function setNombre($nombre) {
        $this->nombre = $this->db->real_escape_string($nombre);
    }

    public function setApellidos($apellidos) {
        $this->apellidos = $this->db->real_escape_string($apellidos);
    }

    public function setEmail($email) {
        $this->email = $this->db->real_escape_string($email);
    }

    public function setPassword($password) {
        $this->password = $this->db->real_escape_string($password);
    }

    /**
     * Encripta y guarda la contraseña.
     *
     * @param string $password Contraseña sin cifrar.
     */
    public function hashPassword($password) {
        $this->password = password_hash(
            $this->db->real_escape_string($password),
            PASSWORD_BCRYPT,
            ['cost' => 4]
        );
    }

    public function setRol($rol) {
        $this->rol = $this->db->real_escape_string($rol);
    }

    public function setImagen($imagen) {
        $this->imagen = $this->db->real_escape_string($imagen);
    }

    public function setEmailVerificado($estado) {
        $this->email_verificado = (int)$estado;
    }

    public function setTokenVerificacion($token) {
        $this->token_verificacion = $this->db->real_escape_string($token);
    }

    // ───────────── MÉTODOS FUNCIONALES ─────────────

    /**
     * Guarda un nuevo usuario en la base de datos.
     * @return bool True si el registro fue exitoso.
     */
    public function save() {
        $rol = $this->rol ?? 'user';
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, rol, imagen, email_verificado, token_verificacion) VALUES (
            '{$this->getNombre()}',
            '{$this->getApellidos()}',
            '{$this->getEmail()}',
            '{$this->getPassword()}',
            '{$rol}',
            '{$this->getImagen()}',
            0,
            '{$this->getTokenVerificacion()}'
        )";

        $save = $this->db->query($sql);
        return $save ? true : false;
    }

    /**
     * Intenta iniciar sesión validando email y contraseña.
     * @return object|array Usuario autenticado o array vacío si falla.
     */
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
            if (password_verify($password, $usuario->password)) {
                $resultado = $usuario;
            }
        }

        return $resultado;
    }

    /**
     * Verifica la cuenta de usuario mediante un token recibido por correo.
     * @param string $token Token de verificación.
     * @return bool True si se verificó correctamente.
     */
    public function verificarEmail($token) {
        $token = $this->db->real_escape_string($token);
        $sql = "UPDATE usuarios 
                SET email_verificado = 1, 
                    token_verificacion = NULL 
                WHERE token_verificacion = '$token'";
        return $this->db->query($sql);
    }
}