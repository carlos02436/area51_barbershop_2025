<?php
require_once __DIR__ . '/../../config/database.php';

class TikTok {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Obtener los últimos videos activos
    public function obtenerVideos($limite = 3) {
        $sql = "SELECT id_tiktok, url, video_id, descripcion, fecha_registro, estado
                FROM tiktok
                WHERE estado = 'activo'
                ORDER BY fecha_registro DESC
                LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}