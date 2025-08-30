<?php
require_once __DIR__ . '/../models/Servicio.php';

class ServicioController {
    private $servicioModel;

    public function __construct() {
        $this->servicioModel = new Servicio();
    }

    public function listarServicios() {
        return $this->servicioModel->obtenerServicios();
    }
}
