<?php
require_once __DIR__ . '/../models/TikTok.php';

class TikTokController {
    private $db;
    private $tiktokModel;

    public function __construct($db) {
        $this->db = $db;                 // Guardamos la conexión PDO
        $this->tiktokModel = new TikTok(); // Pasamos la conexión al modelo
    }

    // Listar los últimos videos activos
    public function listarVideos($limite = 3) {
        return $this->tiktokModel->obtenerVideos($limite);
    }

    // Crear un nuevo TikTok
    public function crear($url, $video_id, $descripcion) {
        $sql = "INSERT INTO tiktok (url, video_id, descripcion, fecha_registro, estado) 
                VALUES (:url, :video_id, :descripcion, NOW(), 'activo')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':url'         => $url,
            ':video_id'    => $video_id,
            ':descripcion' => $descripcion
        ]);
    }

    // Eliminar lógicamente un TikTok (estado = inactivo)
    public function eliminar($id_tiktok) {
        $sql = "UPDATE tiktok SET estado = 'inactivo' WHERE id_tiktok = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_tiktok]);
    }
}