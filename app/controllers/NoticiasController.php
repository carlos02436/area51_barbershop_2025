<?php
require_once __DIR__ . '/../models/Noticias.php';

class NoticiasController {
    private $model;

    public function __construct($db) {
        $this->model = new Noticias($db);
    }

    public function listar($limite = null) {
        return $this->model->listar($limite);
    }

    public function crear($titulo, $contenido, $publicado_por = null) {
        return $this->model->crear($titulo, $contenido, $publicado_por);
    }

    public function ver($id) {
        return $this->model->ver($id);
    }

    public function editar($id, $titulo, $contenido, $publicado_por = null) {
        return $this->model->editar($id, $titulo, $contenido, $publicado_por);
    }

    public function eliminar($id) {
        return $this->model->eliminar($id);
    }
}