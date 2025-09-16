<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Video.php'; // <-- incluir el modelo

class VideoController {

    private $model;

    public function __construct($db) {
        $this->model = new VideoModel($db);
    }

    // Mostrar videos activos
    public function index($limit = 3) {
        return $this->model->getVideosActivos($limit);
    }

    // Crear video
    public function crear($titulo, $link, $publicado_por) {
        return $this->model->crearVideo($titulo, $link, $publicado_por);
    }

    // Editar video
    public function editar($id, $titulo, $link, $publicado_por) {
        return $this->model->editarVideo($id, $titulo, $link, $publicado_por);
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