<?php

namespace Controllers;

use Models\Usuario;
use Libs\Response;

class AuthController
{
    // Iniciar sesión
    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;
    
            if (!$username || !$password) {
                header('Location: /login?error=Usuario y contraseña son requeridos');
                exit;
            }
    
            $user = Usuario::findByUsername($username);
    
            if (!$user || !password_verify($password, $user['CONTRASENA'])) {
                header('Location: /login?error=Usuario o contraseña incorrectos');
                exit;
            }
    
            session_start();
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['username'] = $user['NOMBRE_USUARIO'];
            $_SESSION['role'] = $user['ROL'];
    
            header('Location: /');
            exit;
        }
    
        require __DIR__ . '/../Views/Auth/Login.php';
    }

    // Cerrar sesión
    public static function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        return Response::json(['message' => 'Sesión cerrada exitosamente.']);
    }

    // Verificar si el usuario está autenticado
    public function isAuthenticated()
    {
        session_start();
        if (isset($_SESSION['user_id'])) {
            return Response::json([
                'authenticated' => true,
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'username' => $_SESSION['username'],
                    'role' => $_SESSION['role']
                ]
            ]);
        }

        return Response::json(['authenticated' => false], 401);
    }
}
