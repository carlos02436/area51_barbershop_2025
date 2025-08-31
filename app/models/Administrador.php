<?php
class Administrador {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para obtener administrador por usuario
    public function getAdministradorPorUsuario($usuario) {
        $sql = "SELECT * FROM administradores WHERE usuario === :usuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   // Validar login con usuario y contraseña en texto plano
    public function login($usuario, $password) {
        $sql = "SELECT * FROM administradores WHERE usuario = :usuario AND password = :password LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario'  => $usuario,
            ':password' => $password
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }

    // Procesar login y gestionar sesión
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $stmt = $this->db->prepare("SELECT * FROM administradores WHERE usuario = ? AND password = ?");
            $stmt->execute([$usuario, $password]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                session_start();
                session_regenerate_id(true);
                $_SESSION['admin'] = [
                    'id_admin' => $admin['id_admin'],
                    'nombre'   => $admin['nombre'],
                    'usuario'  => $admin['usuario'],
                    'email'    => $admin['email']
                ];
                header("Location: index.php?page=panel");
                exit;
            } else {
                return "Usuario o contraseña incorrectos.";
            }
        }
        return null;
    }
}