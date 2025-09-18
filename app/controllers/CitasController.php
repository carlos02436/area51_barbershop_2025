<?php
require_once __DIR__ . '/../models/Cita.php';

class CitasController {
    private $citas;

    public function __construct($db) {
        $this->citas = new Citas($db);
    }

    public function listarCitas() {
        return $this->citas->listar();
    }

    public function obtenerCita($id) {
        return $this->citas->obtener($id);
    }

    public function crearCita($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
        // ✅ Validar que todos los campos estén completos
        if(empty($id_cliente) || empty($id_barbero) || empty($id_servicio) || empty($fecha_cita) || empty($hora_cita)){
            return false;
        }

        // Validar disponibilidad
        $ocupadas = $this->citas->horasOcupadas($id_barbero, $fecha_cita);
        if(in_array($hora_cita, $ocupadas)){
            return false;
        }

        // Validaciones especiales por barbero
        $dayOfWeek = date('w', strtotime($fecha_cita));
        if ($id_barbero == 1) { 
            if ($dayOfWeek == 0) return false; 
            if ($hora_cita >= '12:00:00' && $hora_cita <= '14:00:00') return false; 
            if ($this->citas->contarCitasDia($id_barbero, $fecha_cita) >= 8) return false; 
        } else {
            if ($dayOfWeek != 0) { 
                if ($hora_cita < '08:00:00' || $hora_cita > '20:00:00') return false;
            } else { 
                if ($hora_cita < '08:00:00' || $hora_cita > '16:00:00') return false;
            }
        }

        return $this->citas->crear($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);
    }

    public function actualizarCita($id, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
        if (!$this->citas->validarDisponibilidad($id_barbero, $fecha_cita, $hora_cita)) {
            return false;
        }
        return $this->citas->actualizar($id, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);
    }

    public function cancelarCita($id) {
        return $this->citas->cancelar($id);
    }

    public function eliminarCita($id) {
        return $this->citas->eliminar($id);
    }

    public function horasOcupadas($id_barbero, $fecha_cita) {
        return $this->citas->horasOcupadas($id_barbero, $fecha_cita);
    }
}