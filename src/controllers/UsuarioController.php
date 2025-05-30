<?php

require_once __DIR__ . '/../models/usuario.php';

class UsuarioController
{
    public function index()
    {
        echo 'Controlador Usuario, Acción Index';
    }

    // Muestra el formulario de registro
    public function register()
    {
        require_once __DIR__ . '/../views/usuario/register.php';
    }

    // Procesa el registro de un nuevo usuario
    public function save()
    {
        if (isset($_POST)) {
            $nombre = $_POST['name'] ?? false;
            $apellidos = $_POST['surname'] ?? false;
            $email = $_POST['email'] ?? false;
            $password = $_POST['password'] ?? false;

            $errors = [];

            // Validación del nombre
            if (empty($nombre) || is_numeric($nombre) || preg_match('/[0-9]/', $nombre)) {
                $errors['name'] = 'El nombre no es válido';
            }

            // Validación de los apellidos
            if (empty($apellidos) || is_numeric($apellidos) || preg_match('/[0-9]/', $apellidos)) {
                $errors['surname'] = 'Los apellidos no son válidos';
            }

            // Validación del email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'El email no es válido';
            }

            // Validación de la contraseña
            if (empty($password)) {
                $errors['password'] = 'La contraseña no puede estar vacía';
            }

            if (empty($errors)) {
                $usuario = new Usuario();
                $usuario->setNombre($nombre);
                $usuario->setApellidos($apellidos);
                $usuario->setEmail($email);
                $usuario->hashPassword($password);
                
                 // Rol (solo admins pueden asignar)
                if (isset($_SESSION['admin'])){
                    $rol = $_POST['rol'] ?? false;
                    $usuario->setRol($rol);
                }

                // Generar token único para verificación
                $token = bin2hex(random_bytes(16));
                $usuario->setTokenVerificacion($token);

                $save = $usuario->save();

                if ($save) {
                    $_SESSION['register'] = 'pending'; // aún no verificado
    
                    // Enviar correo de verificación
                    require_once 'helpers/mailHelpers.php';
                    enviarCorreoVerificacion($email, $token);
                
                } else {
                    $_SESSION['register'] = 'failed';
                }
                //$_SESSION['register'] = $save ? 'completed' : 'failed';
            
            } else {
                $_SESSION['register'] = 'failed';
                $_SESSION['errors'] = $errors;
            }
        }
        
        header('Location:' . base_url . 'usuario/register');
    }

    // Procesa el inicio de sesión
    
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
                
                // Crear una cookie de sesión con duración de 30 minutos
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

// Cierra la sesión del usuario y elimina la cookie
public function logout()
{
    unset($_SESSION['identity'], $_SESSION['admin']);
    
    // Destruir la cookie de sesión
    if (isset($_COOKIE["user_session"])) {
        setcookie("user_session", "", time() - 3600, "/"); // Expira en el pasado
    }

    header('Location: ' . index_url);
}

// Verifica la inactividad del usuario
public static function checkSessionTimeout()
{
    if (isset($_COOKIE["user_session"])) {
        // Si la cookie expira, cierra la sesión
        if ($_COOKIE["user_session"] != session_id()) {
            unset($_SESSION['identity'], $_SESSION['admin']);
            setcookie("user_session", "", time() - 3600, "/");
            header('Location: ' . base_url . 'usuario/logout');
            exit();
        }
    }
}


public function verificar()
{
    if (isset($_GET['token'])) {
        $token = $_GET['token'];

        $usuario = new Usuario();
        $verificado = $usuario->verificarEmail($token);

        if ($verificado) {
            $_SESSION['verificado'] = 'ok';
        } else {
            $_SESSION['verificado'] = 'fail';
        }
    } else {
        $_SESSION['verificado'] = 'fail';
    }

    require_once __DIR__ . '/../views/usuario/verificacion.php';
}


}
