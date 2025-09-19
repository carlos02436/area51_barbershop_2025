<?php
require_once __DIR__ . '/../../config/database.php';

class Testimonio {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function obtenerTestimonios() {
        $sql = "SELECT id_testimonio, nombre, mensaje, img, fecha_registro
                FROM testimonios
                ORDER BY id_testimonio ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

      // ================== CREAR ==================
    public function crear($nombre, $mensaje, $img = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO testimonios (nombre, mensaje, img, fecha_registro)
                VALUES (:nombre, :mensaje, :img, NOW())
            ");
            $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindValue(':mensaje', $mensaje, PDO::PARAM_STR);
            $stmt->bindValue(':img', $img, PDO::PARAM_STR);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================== VER ==================
    public function ver($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM testimonios WHERE id_testimonio = :id LIMIT 1");
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================== EDITAR ==================
    public function editar($id, $nombre, $mensaje, $img = null) {
        try {
            $stmt = $this->db->prepare("
                UPDATE testimonios
                SET nombre = :nombre,
                    mensaje = :mensaje,
                    img = :img
                WHERE id_testimonio = :id
            ");
            $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindValue(':mensaje', $mensaje, PDO::PARAM_STR);
            $stmt->bindValue(':img', $img, PDO::PARAM_STR);
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================== ELIMINAR ==================
    public function eliminar($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM testimonios WHERE id_testimonio = :id");
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}