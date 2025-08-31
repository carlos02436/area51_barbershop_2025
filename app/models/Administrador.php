<?php
class Administrador {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function login($usuario, $password) {
        $sql = "SELECT * FROM administradores WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $admin = $stmt->fetch();
        return ($admin && $password === $admin['password']) ? $admin : false;
    }
}