<?php
require_once __DIR__ . '/../models/Testimonio.php';

class TestimonioController {
    private $testimonioModel;

    public function __construct() {
        $this->testimonioModel = new Testimonio();
    }

    public function listarTestimonios() {
        return $this->testimonioModel->obtenerTestimonios();
    }
}