<?php
// models/Cita.php
require_once __DIR__ . '/../config/database.php';

class Cita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Crear nueva cita
    public function crear($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita, $estado = 'pendiente') {
        $sql = "INSERT INTO citas (id_cliente, id_barbero, id_servicio, fecha_cita, hora_cita, estado) 
                VALUES (:id_cliente, :id_barbero, :id_servicio, :fecha_cita, :hora_cita, :estado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_cliente' => $id_cliente,
            ':id_barbero' => $id_barbero,
            ':id_servicio' => $id_servicio,
            ':fecha_cita' => $fecha_cita,
            ':hora_cita' => $hora_cita,
            ':estado' => $estado
        ]);
    }

    // Obtener todas las citas
    public function obtenerTodas() {
        $sql = "SELECT c.*, cl.nombre AS cliente, b.nombre AS barbero, s.nombre AS servicio
                FROM citas c
                JOIN clientes cl ON c.id_cliente = cl.id_cliente
                JOIN barberos b ON c.id_barbero = b.id_barbero
                JOIN servicios s ON c.id_servicio = s.id_servicio
                ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Obtener una cita por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM citas WHERE id_cita = :id_cita";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_cita' => $id]);
        return $stmt->fetch();
    }

    // Actualizar una cita
    public function actualizar($id_cita, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita, $estado) {
        $sql = "UPDATE citas 
                SET id_cliente = :id_cliente, id_barbero = :id_barbero, id_servicio = :id_servicio,
                    fecha_cita = :fecha_cita, hora_cita = :hora_cita, estado = :estado
                WHERE id_cita = :id_cita";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_cliente' => $id_cliente,
            ':id_barbero' => $id_barbero,
            ':id_servicio' => $id_servicio,
            ':fecha_cita' => $fecha_cita,
            ':hora_cita' => $hora_cita,
            ':estado' => $estado,
            ':id_cita' => $id_cita
        ]);
    }

    // Eliminar una cita
    public function eliminar($id) {
        $sql = "DELETE FROM citas WHERE id_cita = :id_cita";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_cita' => $id]);
    }
}