<?php
// app/models/Cita.php
class Cita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getServicios() {
        $stmt = $this->db->query("SELECT nombre FROM servicios ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT * FROM citas ORDER BY fecha_cita DESC, hora_cita DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearCita($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM citas WHERE id_barbero=? AND fecha_cita=? AND hora_cita=? AND estado!='cancelada'");
        $stmt->execute([$id_barbero, $fecha_cita, $hora_cita]);
        $row = $stmt->fetch();
        if ($row['total'] > 0) return false;

        // Ejemplo de límite de 8 citas/día para barbero 1
        if ($id_barbero == 1) {
            $stmt2 = $this->db->prepare("SELECT COUNT(*) as total FROM citas WHERE id_barbero=? AND fecha_cita=? AND estado!='cancelada'");
            $stmt2->execute([$id_barbero, $fecha_cita]);
            $row2 = $stmt2->fetch();
            if ($row2['total'] >= 8) return false;
        }

        $stmt3 = $this->db->prepare("INSERT INTO citas (id_cliente,id_barbero,id_servicio,fecha_cita,hora_cita,estado) VALUES (?,?,?,?,?,?)");
        return $stmt3->execute([$id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita, 'pendiente']);
    }

    public function editarCita($id_cita, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita, $estado) {
        $stmt = $this->db->prepare("UPDATE citas SET id_cliente=?,id_barbero=?,id_servicio=?,fecha_cita=?,hora_cita=?,estado=? WHERE id_cita=?");
        return $stmt->execute([$id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita, $estado, $id_cita]);
    }

    public function cancelarCita($id_cita) {
        $stmt = $this->db->prepare("UPDATE citas SET estado='cancelada' WHERE id_cita=?");
        return $stmt->execute([$id_cita]);
    }

    public function getCitasConNombres() {
        $stmt = $this->db->query("
            SELECT c.id_cita, cl.nombre AS nombre_cliente, b.nombre AS nombre_barbero,
                   s.nombre AS nombre_servicio, c.fecha_cita, c.hora_cita, c.estado
            FROM citas c
            INNER JOIN clientes cl ON c.id_cliente = cl.id_cliente
            INNER JOIN barberos b ON c.id_barbero = b.id_barbero
            INNER JOIN servicios s ON c.id_servicio = s.id_servicio
            ORDER BY c.fecha_cita ASC, c.hora_cita ASC
        ");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : []; // siempre retornar array
    }
}