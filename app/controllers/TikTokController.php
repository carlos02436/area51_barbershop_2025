<?php
require_once __DIR__ . '/../models/TikTok.php';

class TikTokController {
    private $db;
    private $tiktokModel;

    public function __construct($db) {
        $this->db = $db;                 // Guardamos la conexión PDO
        $this->tiktokModel = new TikTok(); // Instanciamos el modelo
    }

    // Listar los últimos videos activos
    public function listarVideos($limite = 3) {
        return $this->tiktokModel->obtenerVideos($limite);
    }

    // Obtener un TikTok por ID
    public function ver($id) {
        $stmt = $this->db->prepare("SELECT * FROM tiktok WHERE id_tiktok = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo TikTok
    public function crear($url, $video_id, $descripcion, $publicado_por) {
        $sql = "INSERT INTO tiktok (url, video_id, descripcion, publicado_por, fecha_registro, estado) 
                VALUES (:url, :video_id, :descripcion, :publicado_por, NOW(), 'activo')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':url'          => $url,
            ':video_id'     => $video_id,
            ':descripcion'  => $descripcion,
            ':publicado_por'=> $publicado_por
        ]);
    }

    // Editar un TikTok existente
    public function editar($id, $video_id, $url, $descripcion, $publicado_por) {
        $sql = "UPDATE tiktok 
                SET video_id = :video_id, url = :url, descripcion = :descripcion, publicado_por = :publicado_por 
                WHERE id_tiktok = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':video_id'      => $video_id,
            ':url'           => $url,
            ':descripcion'   => $descripcion,
            ':publicado_por' => $publicado_por,
            ':id'            => $id
        ]);
    }

    // Eliminar lógicamente un TikTok (estado = inactivo)
    public function eliminar($id_tiktok) {
        $sql = "UPDATE tiktok SET estado = 'inactivo' WHERE id_tiktok = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_tiktok]);
    }
}