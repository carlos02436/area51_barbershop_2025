<?php
require_once __DIR__ . '/../models/Testimonio.php';

class TestimoniosController {
    private $testimonioModel;

    public function __construct() {
        $this->testimonioModel = new Testimonio();
    }

    public function listarTestimonios() {
        return $this->testimonioModel->obtenerTestimonios();
    }

    public function crear($nombre, $mensaje, $img = null) {
        return $this->testimonioModel->crear($nombre, $mensaje, $img);
    }

    public function editar($id, $nombre, $mensaje, $img = null) {
        return $this->testimonioModel->editar($id, $nombre, $mensaje, $img);
    }

    public function eliminar($id) {
        return $this->testimonioModel->eliminar($id);
    }
}