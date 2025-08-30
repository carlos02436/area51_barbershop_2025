<?php
require_once __DIR__ . '/../../config/database.php';

class Video {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function obtenerVideos($limite = 3) {
        $sql = "SELECT titulo, url FROM videos LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}