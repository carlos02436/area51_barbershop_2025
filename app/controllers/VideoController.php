<?php
require_once __DIR__ . '/models/VideoModel.php';

class VideoController {

    private $model;

    public function __construct($db) {
        $this->model = new VideoModel($db);
    }

    // Mostrar videos activos
    public function index() {
        return $this->model->getVideosActivos();
    }

    // Crear video
    public function crear($titulo, $link, $publicado_por) {
        return $this->model->crearVideo($titulo, $link, $publicado_por);
    }

    // Editar video
    public function editar($id, $titulo, $link) {
        return $this->model->editarVideo($id, $titulo, $link);
    }

    // Eliminar video (inactivo)
    public function eliminar($id) {
        return $this->model->eliminarVideo($id);
    }

    // Obtener video por id
    public function ver($id) {
        return $this->model->getVideoById($id);
    }
}