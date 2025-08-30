<?php
require_once __DIR__ . '/../../config/database.php';

class Testimonio {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function obtenerTestimonios() {
        $sql = "SELECT nombre, mensaje, img 
                FROM testimonios 
                ORDER BY id_testimonio ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}