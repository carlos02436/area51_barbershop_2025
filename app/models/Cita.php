<?php
class Cita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener todas las citas con nombres relacionados
    public function obtenerTodas() {
        $sql = "SELECT c.id_cita, c.id_cliente, cl.nombre AS nombre_cliente, 
                       c.id_barbero, b.nombre AS nombre_barbero,
                       c.id_servicio, s.nombre AS nombre_servicio,
                       c.fecha_cita, c.hora_cita, c.estado
                FROM citas c
                INNER JOIN clientes cl ON c.id_cliente = cl.id_cliente
                INNER JOIN barberos b ON c.id_barbero = b.id_barbero
                INNER JOIN servicios s ON c.id_servicio = s.id_servicio
                ORDER BY c.fecha_cita, c.hora_cita";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una cita por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM citas WHERE id_cita = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva cita
    public function crear($datos) {
        $sql = "INSERT INTO citas (id_cliente, id_barbero, id_servicio, fecha_cita, hora_cita, estado)
                VALUES (:id_cliente, :id_barbero, :id_servicio, :fecha_cita, :hora_cita, :estado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_cliente' => $datos['id_cliente'],
            ':id_barbero' => $datos['id_barbero'],
            ':id_servicio' => $datos['id_servicio'],
            ':fecha_cita' => $datos['fecha_cita'],
            ':hora_cita' => $datos['hora_cita'],
            ':estado' => $datos['estado'] ?? 'pendiente'
        ]);
    }

    // Actualizar una cita
    public function actualizar($id, $datos) {
        $sql = "UPDATE citas SET 
                    id_cliente = :id_cliente,
                    id_barbero = :id_barbero,
                    id_servicio = :id_servicio,
                    fecha_cita = :fecha_cita,
                    hora_cita = :hora_cita,
                    estado = :estado
                WHERE id_cita = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_cliente' => $datos['id_cliente'],
            ':id_barbero' => $datos['id_barbero'],
            ':id_servicio' => $datos['id_servicio'],
            ':fecha_cita' => $datos['fecha_cita'],
            ':hora_cita' => $datos['hora_cita'],
            ':estado' => $datos['estado'],
            ':id' => $id
        ]);
    }

    // Eliminar una cita
    public function eliminar($id) {
        $sql = "DELETE FROM citas WHERE id_cita = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}