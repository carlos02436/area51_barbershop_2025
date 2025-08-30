<?php
require_once __DIR__ . '/../models/Galeria.php';

class GaleriaController {
    private $galeriaModel;

    public function __construct() {
        $this->galeriaModel = new Galeria();
    }

    public function listarImagenes($limite = 9) {
        return $this->galeriaModel->obtenerImagenes($limite);
    }
}