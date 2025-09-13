<?php
class Cita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Listar todas las citas con datos relacionados (cliente, barbero, servicio e imagen)
    public function obtenerTodas() {
        $sql = "SELECT c.id_cita, c.id_cliente, c.id_barbero, c.id_servicio, c.img_servicio,
                       c.fecha_cita, c.hora_cita, c.estado, c.fecha_creacion,
                       CONCAT(cl.nombre, ' ', COALESCE(cl.apellido,'')) AS cliente,
                       b.nombre AS barbero, s.nombre AS servicio, s.img_servicio AS servicio_img
                FROM citas c
                LEFT JOIN clientes cl ON c.id_cliente = cl.id_cliente
                LEFT JOIN barberos b ON c.id_barbero = b.id_barbero
                LEFT JOIN servicios s ON c.id_servicio = s.id_servicio
                ORDER BY c.fecha_cita ASC, c.hora_cita ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una cita por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM citas WHERE id_cita = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nueva cita (espera array con claves: id_cliente, id_barbero, id_servicio, img_servicio, fecha_cita, hora_cita, estado)
    public function crear(array $datos) {
        $sql = "INSERT INTO citas (id_cliente, id_barbero, id_servicio, img_servicio, fecha_cita, hora_cita, estado, fecha_creacion)
                VALUES (:id_cliente, :id_barbero, :id_servicio, :img_servicio, :fecha_cita, :hora_cita, :estado, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_cliente' => $datos['id_cliente'] ?? null,
            ':id_barbero' => $datos['id_barbero'],
            ':id_servicio' => $datos['id_servicio'],
            ':img_servicio' => $datos['img_servicio'] ?? null,
            ':fecha_cita' => $datos['fecha_cita'],
            ':hora_cita' => $datos['hora_cita'],
            ':estado' => $datos['estado'] ?? 'pendiente'
        ]);
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE citas 
                SET id_cliente = :id_cliente, 
                    id_barbero = :id_barbero, 
                    id_servicio = :id_servicio, 
                    img_servicio = :img_servicio, 
                    fecha_cita = :fecha_cita, 
                    hora_cita = :hora_cita, 
                    estado = :estado
                WHERE id_cita = :id_cita";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $datos['id_cliente']);
        $stmt->bindParam(':id_barbero', $datos['id_barbero']);
        $stmt->bindParam(':id_servicio', $datos['id_servicio']);
        $stmt->bindParam(':img_servicio', $datos['img_servicio']);
        $stmt->bindParam(':fecha_cita', $datos['fecha_cita']);
        $stmt->bindParam(':hora_cita', $datos['hora_cita']);
        $stmt->bindParam(':estado', $datos['estado']); 
        $stmt->bindParam(':id_cita', $id);

        return $stmt->execute();
    }

    public function mostrar($id) {
        $stmt = $this->db->prepare("SELECT * FROM citas WHERE id_cita = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificación de horas, para que no se crucen las citas
    public function existeConflicto($id_barbero, $fecha_cita, $hora_cita, $id_cita = null) {
        $sql = "SELECT COUNT(*) as total 
                FROM citas 
                WHERE id_barbero = :id_barbero 
                AND fecha_cita = :fecha_cita 
                AND hora_cita = :hora_cita";

        // Si estamos editando, excluir la misma cita
        if ($id_cita !== null) {
            $sql .= " AND id_cita != :id_cita";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_barbero', $id_barbero);
        $stmt->bindParam(':fecha_cita', $fecha_cita);
        $stmt->bindParam(':hora_cita', $hora_cita);

        if ($id_cita !== null) {
            $stmt->bindParam(':id_cita', $id_cita);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] > 0; // true si ya hay cita en ese horario
    }

    // Eliminar cita
    public function eliminar($id) {
        $sql = "DELETE FROM citas WHERE id_cita = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}