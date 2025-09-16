<?php
// app/models/Noticias.php

class Noticias {
    private $db;

    /**
     * Constructor recibe una instancia PDO
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Devuelve las noticias ordenadas por fecha_publicacion DESC
     * Si $limite no es null, aplica LIMIT en la consulta.
     */
    public function listar($limite = null) {
        try {
            $sql = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC";
            if ($limite !== null) {
                $sql .= " LIMIT :limite";
            }
            $stmt = $this->db->prepare($sql);

            if ($limite !== null) {
                $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function crear($titulo, $contenido, $publicado_por = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO noticias (titulo, contenido, fecha_publicacion, publicado_por)
                VALUES (:titulo, :contenido, NOW(), :publicado_por)
            ");
            $stmt->bindValue(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindValue(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindValue(':publicado_por', $publicado_por, PDO::PARAM_STR);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function ver($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM noticias WHERE id_noticia = :id LIMIT 1");
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function editar($id, $titulo, $contenido, $publicado_por = null) {
        try {
            $stmt = $this->db->prepare("
                UPDATE noticias
                SET titulo = :titulo,
                    contenido = :contenido,
                    publicado_por = :publicado_por
                WHERE id_noticia = :id
            ");
            $stmt->bindValue(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindValue(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindValue(':publicado_por', $publicado_por, PDO::PARAM_STR);
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM noticias WHERE id_noticia = :id");
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}