<?php
// controllers/CitasController.php
require_once __DIR__ . '/../models/Cita.php';

class CitasController {
    private $citas;

    public function __construct($db) {
        $this->citas = new Citas($db); // Conexión PDO
    }

    // Listar todas las citas
    public function listarCitas() {
        return $this->citas->listar();
    }

    // Obtener una cita por ID
    public function obtenerCita($id) {
        return $this->citas->obtener($id);
    }

    // Crear una nueva cita
    public function crear($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita, $img_servicio = '') {
        return $this->citas->crear($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita, $img_servicio);
    }

    // Actualizar cita
    public function actualizarCita($id, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita, $img_servicio = '') {
        return $this->citas->actualizar($id, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);
    }

    // Cancelar cita
    public function cancelarCita($id) {
        return $this->citas->cancelar($id);
    }

    // Eliminar cita
    public function eliminarCita($id) {
        return $this->citas->eliminar($id);
    }

    // Delegados para la vista
    public function horasOcupadas($id_barbero, $fecha_cita) {
        return $this->citas->horasOcupadas($id_barbero, $fecha_cita);
    }

    public function contarCitasPorBarberoFecha($id_barbero, $fecha) {
        return $this->citas->contarCitasPorBarberoFecha($id_barbero, $fecha);
    }

    public function contarCitasDia($id_barbero, $fecha_cita) {
        return $this->citas->contarCitasDia($id_barbero, $fecha_cita);
    }

    // Última cita creada
    public function ultimaCita() {
        return $this->citas->ultimaCita();
    }
    
    // Obtener datos relacionados
    public function getCliente($id) {
        return $this->citas->getCliente($id);
    }

    public function getBarbero($id) {
        return $this->citas->getBarbero($id);
    }

    public function getServicio($id) {
        return $this->citas->getServicio($id);
    }
}