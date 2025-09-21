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
            SELECT c.*, 
                cl.nombre as cliente, cl.apellido, cl.telefono as telefono_cliente,
                b.nombre as barbero, b.telefono as telefono_barbero,
                s.nombre as servicio, s.img_servicio
            FROM citas c
            INNER JOIN clientes cl ON c.id_cliente = cl.id_cliente
            INNER JOIN barberos b ON c.id_barbero = b.id_barbero
            INNER JOIN servicios s ON c.id_servicio = s.id_servicio
            WHERE c.id_cita = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear cita (ahora devuelve el ID insertado)
    public function crear($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita) {
        $stmt = $this->db->prepare("
            INSERT INTO citas (id_cliente, id_barbero, id_servicio, fecha_cita, hora_cita, estado, fecha_creacion)
            VALUES (?, ?, ?, ?, ?, 'pendiente', NOW())
        ");
        $ok = $stmt->execute([$id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita]);

        if ($ok) {
            return $this->db->lastInsertId(); // ✅ devuelve ID de la cita creada
        }
        return false;
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

    // Eliminar cita
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM citas WHERE id_cita = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Horas ocupadas
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
        return (int)$stmt->fetchColumn();
    }

    // Validar disponibilidad
    public function validarDisponibilidad($id_barbero, $fecha_cita, $hora_cita) {
        $dayOfWeek = date('w', strtotime($fecha_cita));
        $hora_cita = date('H:i:s', strtotime($hora_cita));

        if (in_array($hora_cita, $this->horasOcupadas($id_barbero, $fecha_cita))) {
            return false;
        }

        if ($id_barbero == 1 && $this->contarCitasDia($id_barbero, $fecha_cita) >= 8) {
            return false;
        }

        if ($id_barbero == 1) {
            if ($dayOfWeek == 0) {
                return false; // domingo
            }
            if ($hora_cita >= '12:00:00' && $hora_cita < '14:00:00') {
                return false; // almuerzo
            }
        }

        return true;
    }

    // Última cita creada (con teléfonos)
    public function ultimaCita() {
        $stmt = $this->db->query("
            SELECT 
                c.id_cita,
                cli.nombre AS cliente,
                cli.apellido AS apellido,
                cli.telefono AS telefono_cliente,
                b.nombre AS barbero,
                b.telefono AS telefono_barbero,
                s.nombre AS servicio,
                c.fecha_cita,
                c.hora_cita,
                c.img_servicio
            FROM citas c
            INNER JOIN clientes cli ON c.id_cliente = cli.id_cliente
            INNER JOIN barberos b ON c.id_barbero = b.id_barbero
            INNER JOIN servicios s ON c.id_servicio = s.id_servicio
            ORDER BY c.id_cita DESC
            LIMIT 1
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function contarCitasPorBarberoFecha($id_barbero, $fecha) {
        $sql = "SELECT COUNT(*) 
                FROM citas 
                WHERE id_barbero = :id_barbero 
                AND fecha_cita = :fecha";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_barbero' => $id_barbero,
            ':fecha' => $fecha
        ]);
        return (int)$stmt->fetchColumn();
    }

    // dentro de class Citas { ... }
    public function getCliente($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBarbero($id) {
        $stmt = $this->db->prepare("SELECT * FROM barberos WHERE id_barbero = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getServicio($id) {
        $stmt = $this->db->prepare("SELECT * FROM servicios WHERE id_servicio = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}