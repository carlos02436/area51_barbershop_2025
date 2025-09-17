<?php
class Citas {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Listar todas las citas
    public function listar() {
        $stmt = $this->db->prepare("
            SELECT 
                c.id_cita, 
                cli.nombre AS nombre_cliente, 
                cli.apellido AS apellido_cliente, 
                b.nombre AS barbero, 
                s.nombre AS servicio, 
                s.img_servicio AS servicio_imagen,
                c.fecha_cita, 
                c.hora_cita, 
                c.estado
            FROM citas c
            LEFT JOIN clientes cli ON c.id_cliente = cli.id_cliente
            LEFT JOIN barberos b ON c.id_barbero = b.id_barbero
            LEFT JOIN servicios s ON c.id_servicio = s.id_servicio
            ORDER BY c.fecha_cita DESC, c.hora_cita DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una cita
    public function obtener($id) {
        $stmt = $this->db->prepare("
            SELECT 
                c.*, 
                cl.nombre AS nombre_cliente, 
                cl.apellido AS apellido_cliente,
                b.nombre AS nombre_barbero, 
                s.nombre AS nombre_servicio
            FROM citas c
            INNER JOIN clientes cl ON c.id_cliente = cl.id_cliente
            INNER JOIN barberos b ON c.id_barbero = b.id_barbero
            INNER JOIN servicios s ON c.id_servicio = s.id_servicio
            WHERE c.id_cita = :id
            LIMIT 1
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear cita
    public function crear($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
        $stmt = $this->db->prepare("
            INSERT INTO citas (id_cliente, id_barbero, id_servicio, fecha_cita, hora_cita)
            VALUES (:id_cliente, :id_barbero, :id_servicio, :fecha_cita, :hora_cita)
        ");
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_barbero', $id_barbero);
        $stmt->bindParam(':id_servicio', $id_servicio);
        $stmt->bindParam(':fecha_cita', $fecha_cita);
        $stmt->bindParam(':hora_cita', $hora_cita);
        return $stmt->execute();
    }

    // Actualizar cita
    public function actualizar($id, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
        $stmt = $this->db->prepare("
            UPDATE citas 
            SET id_cliente = :id_cliente,
                id_barbero = :id_barbero,
                id_servicio = :id_servicio,
                fecha_cita = :fecha_cita,
                hora_cita = :hora_cita
            WHERE id_cita = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_barbero', $id_barbero);
        $stmt->bindParam(':id_servicio', $id_servicio);
        $stmt->bindParam(':fecha_cita', $fecha_cita);
        $stmt->bindParam(':hora_cita', $hora_cita);
        return $stmt->execute();
    }

    // Cancelar cita
    public function cancelar($id) {
        $stmt = $this->db->prepare("UPDATE citas SET estado = 'cancelada' WHERE id_cita = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Obtener horas ocupadas para un barbero y fecha
    public function horasOcupadas($id_barbero, $fecha_cita) {
        $stmt = $this->db->prepare("
            SELECT hora_cita 
            FROM citas 
            WHERE id_barbero = :id_barbero 
              AND fecha_cita = :fecha_cita
              AND estado IN ('pendiente','confirmada','realizada')
        ");
        $stmt->bindParam(':id_barbero', $id_barbero, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_cita', $fecha_cita);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Contar citas de un barbero en un día
    public function contarCitasDia($id_barbero, $fecha_cita) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM citas 
            WHERE id_barbero = :id_barbero 
              AND fecha_cita = :fecha_cita
              AND estado IN ('pendiente','confirmada','realizada')
        ");
        $stmt->bindParam(':id_barbero', $id_barbero, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_cita', $fecha_cita);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function eliminar($id) {
        $sql = "DELETE FROM citas WHERE id_cita = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}