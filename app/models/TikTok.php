<?php
require_once __DIR__ . '/../../config/database.php';

class TikTok {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function obtenerVideos($limite = 10) {
        $sql = "SELECT url, video_id, descripcion 
                FROM tiktok 
                ORDER BY id_tiktok ASC 
                LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
