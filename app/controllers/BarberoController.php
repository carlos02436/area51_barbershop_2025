<?php
// app/controllers/BarberoController.php
require_once 'app/models/Barbero.php';

class BarberoController {
    private $barberoModel;

    // Recibe la conexión PDO y la pasa al modelo
    public function __construct($db) {
        $this->barberoModel = new Barbero($db);
    }

    // Mostrar listado de barberos
    public function index() {
        $barberos = $this->barberoModel->obtenerBarberos();
        require 'app/views/barberos/index.php';
    }

    // Mostrar detalle de un barbero
    public function detalle($id) {
        $barbero = $this->barberoModel->obtenerBarberoPorId($id);
        require 'app/views/barberos/detalle.php';
    }
}