<?php
// app/models/Noticias.php

class Noticias {
    private $db;

    /**
     * Constructor recibe una instancia PDO
     *
     * @param PDO $db
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Devuelve todas las noticias ordenadas por fecha_publicacion DESC
     *
     * @return array|false
     */
    public function listar() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM noticias ORDER BY fecha_publicacion DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Puedes loguear el error si lo deseas
            return false;
        }
    }

    /**
     * Crea una noticia. Devuelve el id insertado o false en error.
     *
     * @param string $titulo
     * @param string $contenido
     * @param string|null $publicado_por
     * @return string|false
     */
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

    /**
     * Obtiene una noticia por id_noticia
     *
     * @param int $id
     * @return array|false
     */
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

    /**
     * Actualiza una noticia. Devuelve true/false según éxito.
     *
     * @param int $id
     * @param string $titulo
     * @param string $contenido
     * @param string|null $publicado_por
     * @return bool
     */
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

    /**
     * Elimina una noticia por id_noticia. Devuelve true/false.
     *
     * @param int $id
     * @return bool
     */
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