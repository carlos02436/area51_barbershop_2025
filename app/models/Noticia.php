<?php
// app/models/Noticia.php
require_once __DIR__ . '/../../config/database.php';

class Noticia {
    private $db;

    public function __construct() {
        global $db; // Usamos la conexión PDO de config/database.php
        $this->db = $db;
    }

    public function obtenerNoticias($limite = 3) {
        $sql = "SELECT titulo, contenido, fecha_publicacion 
                FROM noticias 
                ORDER BY fecha_publicacion DESC 
                LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}