<?php
class Administrador {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    // Método para obtener administrador por usuario
    public function getAdministradorPorUsuario($usuario) {
        $sql = "SELECT * FROM administradores WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método de login optimizado y seguro
    public function login($usuario, $password) {
        $admin = $this->getAdministradorPorUsuario($usuario);
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }
}