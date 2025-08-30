<?php
require_once __DIR__ . '/../../config/database.php';

class Servicio {
    private $db;

    public function __construct() {
        global $db; // Usamos la conexión PDO desde database.php
        $this->db = $db;
    }

    public function obtenerServicios() {
        $sql = "SELECT img_servicio, nombre, descripcion, precio, observacion FROM servicios";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(); // Devuelve un array de servicios
    }
}
