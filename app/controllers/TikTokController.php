<?php
require_once __DIR__ . '/../models/TikTok.php';

class TikTokController {
    private $db;
    private $tiktokModel;

    public function __construct($db) {
        $this->db = $db;
        $this->tiktokModel = new TikTok();
    }

    // Función privada para limpiar la URL y quitar parámetros
    private function limpiarUrlTikTok($url) {
        $parsedUrl = parse_url($url);
        return $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];
    }

    // Función privada para extraer el video_id de la URL TikTok
    private function extraerVideoId($url) {
        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['path'])) {
            return null;
        }
        
        // Ruta típica: /@usuario/video/7546526690428620038
        $partes = explode('/', trim($parsedUrl['path'], '/'));
        
        $videoIndex = array_search('video', $partes);
        if ($videoIndex !== false && isset($partes[$videoIndex + 1])) {
            return $partes[$videoIndex + 1];
        }
        
        return null;
    }

    // Listar los últimos videos activos
    public function listarVideos($limite = 3) {
        $sql = "SELECT * FROM tiktok WHERE estado = 'activo' ORDER BY fecha_registro DESC LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un TikTok por ID
    public function ver($id) {
        $stmt = $this->db->prepare("SELECT * FROM tiktok WHERE id_tiktok = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo TikTok con URL limpia y video_id extraído automáticamente
    public function crear($url, $descripcion, $publicado_por) {
        $urlLimpia = $this->limpiarUrlTikTok($url);
        $video_id = $this->extraerVideoId($urlLimpia);

        if (!$video_id) {
            throw new Exception("URL inválida, no se pudo extraer video_id");
        }

        $sql = "INSERT INTO tiktok (url, video_id, descripcion, publicado_por, fecha_registro, estado) 
                VALUES (:url, :video_id, :descripcion, :publicado_por, NOW(), 'activo')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':url'          => $urlLimpia,
            ':video_id'     => $video_id,
            ':descripcion'  => $descripcion,
            ':publicado_por'=> $publicado_por
        ]);
    }

    // Editar un TikTok existente, actualizando URL y video_id
    public function editar($id, $url, $descripcion, $publicado_por) {
        $urlLimpia = $this->limpiarUrlTikTok($url);
        $video_id = $this->extraerVideoId($urlLimpia);

        if (!$video_id) {
            throw new Exception("URL inválida, no se pudo extraer video_id");
        }

        $sql = "UPDATE tiktok 
                SET url = :url, video_id = :video_id, descripcion = :descripcion, publicado_por = :publicado_por 
                WHERE id_tiktok = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':url'           => $urlLimpia,
            ':video_id'      => $video_id,
            ':descripcion'   => $descripcion,
            ':publicado_por' => $publicado_por,
            ':id'            => $id
        ]);
    }

    // Eliminar lógicamente un TikTok (estado = inactivo)
    public function eliminar($id_tiktok) {
        $sql = "UPDATE tiktok SET estado = 'inactivo' WHERE id_tiktok = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_tiktok' => $id_tiktok]);
    }
}