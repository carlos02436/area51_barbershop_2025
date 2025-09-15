<?php
require_once __DIR__ . '/../../../config/database.php';

class VideoModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener los 3 videos activos más recientes
    public function getVideosActivos($limit = 3) {
        $sql = "SELECT * FROM videos WHERE estado = 'activo' ORDER BY fecha_publicacion DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear nuevo video
    public function crearVideo($titulo, $link, $publicado_por) {
        $sql = "INSERT INTO videos (titulo, link, publicado_por, estado) VALUES (:titulo, :link, :publicado_por, 'activo')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':link' => $link,
            ':publicado_por' => $publicado_por
        ]);
        return $this->db->lastInsertId();
    }

    // Obtener video por id
    public function getVideoById($id) {
        $sql = "SELECT * FROM videos WHERE id_video = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Editar video
    public function editarVideo($id, $titulo, $link) {
        $sql = "UPDATE videos SET titulo = :titulo, link = :link WHERE id_video = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':link' => $link,
            ':id' => $id
        ]);
    }

    // Eliminar video (inactivar)
    public function eliminarVideo($id) {
        $sql = "UPDATE videos SET estado = 'inactivo' WHERE id_video = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

}