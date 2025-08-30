<?php
require_once __DIR__ . '/../models/Barbero.php';

class BarberoController {
    private $barberoModel;

    public function __construct() {
        $this->barberoModel = new Barbero();
    }

    public function listarBarberos($limite = 5) {
        return $this->barberoModel->obtenerBarberos($limite);
    }
}
