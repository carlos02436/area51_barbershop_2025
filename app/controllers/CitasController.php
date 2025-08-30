<?php
// controllers/CitasController.php
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../config/database.php';

class CitasController {
    private $cita;

    public function __construct($db) {
        $this->cita = new Cita($db);
    }

    // Mostrar todas las citas
    public function index() {
        return $this->cita->obtenerTodas();
    }

    // Crear nueva cita
    public function store($data) {
        return $this->cita->crear(
            $data['id_cliente'],
            $data['id_barbero'],
            $data['id_servicio'],
            $data['fecha_cita'],
            $data['hora_cita'],
            $data['estado'] ?? 'pendiente'
        );
    }

    // Mostrar una cita
    public function show($id) {
        return $this->cita->obtenerPorId($id);
    }

    // Actualizar cita
    public function update($id, $data) {
        return $this->cita->actualizar(
            $id,
            $data['id_cliente'],
            $data['id_barbero'],
            $data['id_servicio'],
            $data['fecha_cita'],
            $data['hora_cita'],
            $data['estado']
        );
    }

    // Eliminar cita
    public function destroy($id) {
        return $this->cita->eliminar($id);
    }
}
