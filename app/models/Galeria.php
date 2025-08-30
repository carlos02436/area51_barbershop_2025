<?php
require_once __DIR__ . '/../../config/database.php';

class Galeria {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function obtenerImagenes($limite = 9) {
        $sql = "SELECT img_galeria, nombre_galeria FROM galeria ORDER BY id_galeria ASC LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}