<?php
// controllers/CitasController.php
require_once __DIR__ . '/../models/Cita.php';

class CitasController {
    private $citas;

    public function __construct($db) {
        $this->citas = new Citas($db);
    }

    // Listar todas las citas
    public function listarCitas() {
        return $this->citas->listar();
    }

    // Obtener una cita por id
    public function obtenerCita($id) {
        return $this->citas->obtener($id);
    }

    // Crear una nueva cita (con imagen y WhatsApp)
    public function crearCita($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
        if (empty($id_cliente) || empty($id_barbero) || empty($id_servicio) || empty($fecha_cita) || empty($hora_cita)) {
            return false;
        }

        // Validar disponibilidad
        if (!$this->citas->validarDisponibilidad($id_barbero, $fecha_cita, $hora_cita)) {
            return false;
        }

        // Crear cita (sin imagen)
        $citaId = $this->citas->crear($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);

        if ($citaId) {
            // Traer datos completos de la cita (con JOIN para obtener la imagen)
            $cita = $this->citas->obtener($citaId);

            return true;
        }

        return false;
    }

    // Actualizar cita
    public function actualizarCita($id, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
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

    public function contarCitasDia($id_barbero, $fecha_cita) {
        return $this->citas->contarCitasDia($id_barbero, $fecha_cita);
    }

    // Última cita creada
    public function ultimaCita() {
        return $this->citas->ultimaCita();
    }
}