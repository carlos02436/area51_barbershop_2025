<?php
class Noticias {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function listar() {
        $stmt = $this->db->prepare("
            SELECT n.*, a.nombre AS nombre_admin 
            FROM noticias n
            LEFT JOIN administradores a ON n.publicado_por = a.id_admin
            WHERE n.estado = 'activo'
            ORDER BY n.fecha_publicacion DESC
            LIMIT 3
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($id) {
        $stmt = $this->db->prepare("
            SELECT n.*, a.nombre AS nombre_admin
            FROM noticias n
            LEFT JOIN administradores a ON n.publicado_por = a.id_admin
            WHERE id_noticia = :id
            LIMIT 1
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($titulo, $contenido, $id_admin) {
        $stmt = $this->db->prepare("
            INSERT INTO noticias (titulo, contenido, publicado_por, estado)
            VALUES (:titulo, :contenido, :id_admin, 'activo')
        ");
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':contenido', $contenido);
        $stmt->bindParam(':id_admin', $id_admin);
        return $stmt->execute();
    }

    public function actualizar($id, $titulo, $contenido) {
        $stmt = $this->db->prepare("
            UPDATE noticias 
            SET titulo = :titulo, contenido = :contenido 
            WHERE id_noticia = :id
        ");
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':contenido', $contenido);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("UPDATE noticias SET estado = 'inactivo' WHERE id_noticia = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}