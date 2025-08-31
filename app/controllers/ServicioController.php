<?php
require_once __DIR__ . '/../models/Servicio.php';

class ServicioController {
    private $servicioModel;

    public function __construct() {
        $this->servicioModel = new Servicio();
    }

    // Listar todos los servicios
    public function listarServicios() {
        return $this->servicioModel->obtenerServicios();
    }

    // Obtener un servicio por ID
    public function obtenerServicio($id) {
        if (!is_numeric($id)) {
            throw new Exception("ID inválido");
        }
        return $this->servicioModel->obtenerServicioPorId($id);
    }

    // Crear nuevo servicio
    public function crearServicio($data) {
        if (empty($data['nombre']) || empty($data['precio'])) {
            throw new Exception("Datos incompletos para crear el servicio");
        }
        return $this->servicioModel->crearServicio($data);
    }

    // Actualizar servicio
    public function actualizarServicio($id, $data) {
        if (!is_numeric($id)) {
            throw new Exception("ID inválido");
        }
        return $this->servicioModel->actualizarServicio($id, $data);
    }

    // Eliminar servicio
    public function eliminarServicio($id) {
        if (!is_numeric($id)) {
            throw new Exception("ID inválido");
        }
        return $this->servicioModel->eliminarServicio($id);
    }
}

