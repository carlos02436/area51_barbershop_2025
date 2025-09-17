<?php
require_once __DIR__ . '/../models/Cita.php';

class CitasController {
    private $db;
    private $citas;

    public function __construct($db) {
        $this->db = $db;
        $this->citas = new Citas($db);
    }

    public function listarCitas() {
        return $this->citas->listar();
    }

    public function obtenerCita($id) {
        return $this->citas->obtener($id);
    }

    public function crearCita($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
        // Validar disponibilidad antes de crear
        $ocupadas = $this->citas->horasOcupadas($id_barbero, $fecha_cita);

        if (in_array($hora_cita, $ocupadas)) {
            return false; // Hora ya ocupada
        }

        // Validaciones especiales por barbero
        $dayOfWeek = date('w', strtotime($fecha_cita));
        if ($id_barbero == 1) { // Yeison Sarmiento
            if ($dayOfWeek == 0) return false; // domingo no trabaja
            if ($hora_cita >= '11:00:00' && $hora_cita <= '14:00:00') return false; // bloque hora lunch
            if ($this->citas->contarCitasDia($id_barbero, $fecha_cita) >= 8) return false; // max 8 citas
        } else {
            // Otros barberos
            if ($dayOfWeek != 0) { // lunes a viernes 08-20
                if ($hora_cita < '08:00:00' || $hora_cita > '20:00:00') return false;
            } else { // domingo 08-16
                if ($hora_cita < '08:00:00' || $hora_cita > '16:00:00') return false;
            }
        }

        return $this->citas->crear($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);
    }

    public function actualizarCita($id, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
        return $this->citas->actualizar($id, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);
    }

    public function cancelarCita($id) {
        return $this->citas->cancelar($id);
    }

    public function horasOcupadas($id_barbero, $fecha_cita) {
        return $this->citas->horasOcupadas($id_barbero, $fecha_cita);
    }
}