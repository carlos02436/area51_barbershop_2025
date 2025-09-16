<?php
require_once __DIR__ . '/../models/PQRS.php';

class PQRSController {
    private $pqrsModel;

    public function __construct($db) {
        $this->pqrsModel = new PQRS($db);
    }

    public function listar() {
        return $this->pqrsModel->listar();
    }

    public function ver($id) {
        return $this->pqrsModel->ver($id);
    }

    public function crear($nombre, $apellidos, $email, $tipo, $mensaje) {
        return $this->pqrsModel->crear($nombre, $apellidos, $email, $tipo, $mensaje);
    }

    public function editar($id, $nombre, $apellidos, $email, $tipo, $mensaje, $estado) {
        return $this->pqrsModel->editar($id, $nombre, $apellidos, $email, $tipo, $mensaje, $estado);
    }

    public function eliminar($id) {
        return $this->pqrsModel->eliminar($id);
    }
}