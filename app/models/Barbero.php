<?php 
require_once __DIR__ . '/../../config/database.php';

class Barbero {
    private $db;

    public function __construct() {
        global $db; // Usamos PDO de database.php
        $this->db = $db;
    }

    // Obtener todos los barberos
    public function getBarberos() {
        $stmt = $this->db->query("SELECT id_barbero, nombre FROM barberos ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener barberos con límite y más info
    public function obtenerBarberos($limite = 5) {
        $sql = "SELECT id_barbero, nombre, especialidad, img_barberos 
                FROM barberos 
                ORDER BY id_barbero ASC 
                LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT id_barbero, nombre FROM barberos";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}