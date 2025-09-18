<?php
// app/controllers/AuthController.php
require_once __DIR__ . '/../../config/database.php';

class AuthController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario']);
            $password = trim($_POST['password']);

            $sql = "SELECT * FROM administradores WHERE usuario = :usuario LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $admin = $stmt->fetch();

            if ($admin && $admin['password'] === $password) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['admin_usuario'] = $admin['usuario'];

                header("Location: index.php?page=panel");
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
        }

        include __DIR__ . '/../views/login.php';
    }

    public function logout() {
        session_start();
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        // Evitar caché en logout
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");

        header("Location: index.php?page=login");
        exit();
    }
}