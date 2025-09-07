<?php
require_once __DIR__ . '/../models/Servicio.php';

class ServicioController {
    private $servicioModel;

    public function __construct() {
        $this->servicioModel = new Servicio();
    }

    // 🔹 Listar todos los servicios
    public function listarServicios() {
        return $this->servicioModel->obtenerServicios();
    }

    // 🔹 Obtener un servicio por ID
    public function mostrarServicio($id) {
        return $this->servicioModel->obtenerServicioPorId($id);
    }

    // 🔹 Crear un nuevo servicio
    public function crearServicio($data) {
        return $this->servicioModel->crearServicio($data);
    }

    // 🔹 Actualizar un servicio
    public function actualizarServicio($id, $data) {
        return $this->servicioModel->actualizarServicio($id, $data);
    }

    // 🔹 Eliminar un servicio
    public function eliminarServicio($id) {
        return $this->servicioModel->eliminarServicio($id);
    }
}