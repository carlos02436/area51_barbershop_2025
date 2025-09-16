<?php
require_once __DIR__ . '/../models/Noticias.php';

class NoticiasController {
    private $model;

    public function __construct($db) {
        $this->model = new Noticias($db);
    }

    // ================== LISTAR NOTICIAS ==================
    public function listar() {
        return $this->model->listar();
    }

    // ================== CREAR NOTICIA ==================
    public function crear($titulo, $contenido, $publicado_por = null) {
        return $this->model->crear($titulo, $contenido, $publicado_por);
    }

    // ================== VER UNA NOTICIA ==================
    public function ver($id) {
        return $this->model->ver($id);
    }

    // ================== EDITAR NOTICIA ==================
    public function editar($id, $titulo, $contenido, $publicado_por = null) {
        return $this->model->editar($id, $titulo, $contenido, $publicado_por);
    }

    // ================== ELIMINAR NOTICIA ==================
    public function eliminar($id) {
        return $this->model->eliminar($id);
    }
}
