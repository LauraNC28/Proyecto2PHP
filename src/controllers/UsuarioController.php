<?php

require_once __DIR__ . '/../models/usuario.php';

/**
 * Clase UsuarioController
 * 
 * Controlador encargado de gestionar las acciones relacionadas con los usuarios:
 * registro, inicio/cierre de sesión, verificación de email y control de sesión.
 */
class UsuarioController
{
    /**
     * Método por defecto. Útil para pruebas básicas.
     *
     * @return void
     */
    public function index()
    {
        echo 'Controlador Usuario, Acción Index';
    }

    /**
     * Muestra el formulario de registro de usuario.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . '/../views/usuario/register.php';
    }

    /**
     * Procesa el registro de un nuevo usuario:
     * - Valida datos
     * - Crea el usuario en la base de datos
     * - Genera un token
     * - Envía correo de verificación
     *
     * @return void
     */
    public function save()
    {
        if (isset($_POST)) {
            $nombre = $_POST['name'] ?? false;
            $apellidos = $_POST['surname'] ?? false;
            $email = $_POST['email'] ?? false;
            $password = $_POST['password'] ?? false;

            $errors = [];

            // Validaciones básicas
            if (empty($nombre) || is_numeric($nombre) || preg_match('/[0-9]/', $nombre)) {
                $errors['name'] = 'El nombre no es válido';
            }
            if (empty($apellidos) || is_numeric($apellidos) || preg_match('/[0-9]/', $apellidos)) {
                $errors['surname'] = 'Los apellidos no son válidos';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'El email no es válido';
            }
            if (empty($password)) {
                $errors['password'] = 'La contraseña no puede estar vacía';
            }

            if (empty($errors)) {
                $usuario = new Usuario();
                $usuario->setNombre($nombre);
                $usuario->setApellidos($apellidos);
                $usuario->setEmail($email);
                $usuario->hashPassword($password);

                // Solo un admin puede establecer el rol
                if (isset($_SESSION['admin'])) {
                    $rol = $_POST['rol'] ?? false;
                    $usuario->setRol($rol);
                }

                // Generar token de verificación y guardarlo
                $token = bin2hex(random_bytes(16));
                $usuario->setTokenVerificacion($token);

                $save = $usuario->save();

                if ($save) {
                    $_SESSION['register'] = 'pending'; // Aún no verificado
                    require_once 'helpers/mailHelpers.php';
                    enviarCorreoVerificacion($email, $token);
                } else {
                    $_SESSION['register'] = 'failed';
                }
            } else {
                $_SESSION['register'] = 'failed';
                $_SESSION['errors'] = $errors;
            }
        }

        header('Location:' . base_url . 'usuario/register');
    }

    /**
     * Procesa el inicio de sesión.
     * Solo permite acceso si el email ha sido verificado.
     *
     * @return void
     */
    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $usuario = new Usuario();
            $usuario->setEmail($_POST['email']);
            $usuario->setPassword($_POST['password']);
            $identity = $usuario->login();

            if (!empty($identity)) {
                if ($identity->email_verificado == 1) {
                    $_SESSION['identity'] = $identity;
                    if ($identity->rol == 'admin') {
                        $_SESSION['admin'] = true;
                    }

                    // Establece cookie de sesión por 30 minutos
                    setcookie("user_session", session_id(), time() + (30 * 60), "/");
                } else {
                    $_SESSION['error_login'] = 'Debes verificar tu correo antes de iniciar sesión.';
                }
            } else {
                $_SESSION['error_login'] = 'Credenciales incorrectas.';
            }
        }

        header('Location: ' . index_url);
    }

    /**
     * Cierra la sesión del usuario y elimina la cookie de sesión.
     *
     * @return void
     */
    public function logout()
    {
        unset($_SESSION['identity'], $_SESSION['admin']);

        if (isset($_COOKIE["user_session"])) {
            setcookie("user_session", "", time() - 3600, "/"); // La invalida
        }

        header('Location: ' . index_url);
    }

    /**
     * Verifica si la cookie de sesión sigue activa.
     * Si no lo está, fuerza el cierre de sesión.
     *
     * @return void
     */
    public static function checkSessionTimeout()
    {
        if (isset($_COOKIE["user_session"])) {
            if ($_COOKIE["user_session"] != session_id()) {
                unset($_SESSION['identity'], $_SESSION['admin']);
                setcookie("user_session", "", time() - 3600, "/");
                header('Location:' . base_url . 'usuario/logout');
                exit();
            }
        }
    }

    /**
     * Verifica el token de activación enviado por correo.
     * Si es válido, activa la cuenta (email_verificado = 1).
     *
     * @return void
     */
    public function verificar()
    {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];

            $usuario = new Usuario();
            $verificado = $usuario->verificarEmail($token);

            $_SESSION['verificado'] = $verificado ? 'ok' : 'fail';
        } else {
            $_SESSION['verificado'] = 'fail';
        }

        require_once __DIR__ . '/../views/usuario/verificacion.php';
    }
}