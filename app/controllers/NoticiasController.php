<?php
require_once __DIR__ . '/../models/Noticias.php';

class NoticiasController {
    private $db;
    private $noticias;

    public function __construct($db) {
        $this->db = $db;
        $this->noticias = new Noticias($db);
    }

    public function listarNoticias() {
        return $this->noticias->listar();
    }

    public function obtenerNoticia($id) {
        return $this->noticias->obtener($id);
    }

    public function crearNoticia($titulo, $contenido, $id_admin) {
        return $this->noticias->crear($titulo, $contenido, $id_admin);
    }

    public function actualizarNoticia($id, $titulo, $contenido) {
        return $this->noticias->actualizar($id, $titulo, $contenido);
    }

    public function eliminarNoticia($id) {
        return $this->noticias->eliminar($id);
    }
}