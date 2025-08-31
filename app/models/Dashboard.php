<?php

class Dashboard
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    // Obtener todos los registros del dashboard
    public function all()
    {
        $sql = "SELECT * FROM dashboard";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un registro por id_dashboard
    public function find($id)
    {
        $sql = "SELECT * FROM dashboard WHERE id_dashboard = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo registro
    public function create($data)
    {
        $sql = "INSERT INTO dashboard (id_barbero, fecha_reporte, horas_trabajadas, clientes_atendidos, ingresos_generados, creado_en)
                VALUES (:id_barbero, :fecha_reporte, :horas_trabajadas, :clientes_atendidos, :ingresos_generados, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_barbero', $data['id_barbero'], PDO::PARAM_INT);
        $stmt->bindParam(':fecha_reporte', $data['fecha_reporte']);
        $stmt->bindParam(':horas_trabajadas', $data['horas_trabajadas']);
        $stmt->bindParam(':clientes_atendidos', $data['clientes_atendidos'], PDO::PARAM_INT);
        $stmt->bindParam(':ingresos_generados', $data['ingresos_generados']);
        return $stmt->execute();
    }

    // Actualizar un registro existente
    public function update($id, $data)
    {
        $sql = "UPDATE dashboard SET 
                    id_barbero = :id_barbero,
                    fecha_reporte = :fecha_reporte,
                    horas_trabajadas = :horas_trabajadas,
                    clientes_atendidos = :clientes_atendidos,
                    ingresos_generados = :ingresos_generados
                WHERE id_dashboard = :id_dashboard";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_barbero', $data['id_barbero'], PDO::PARAM_INT);
        $stmt->bindParam(':fecha_reporte', $data['fecha_reporte']);
        $stmt->bindParam(':horas_trabajadas', $data['horas_trabajadas']);
        $stmt->bindParam(':clientes_atendidos', $data['clientes_atendidos'], PDO::PARAM_INT);
        $stmt->bindParam(':ingresos_generados', $data['ingresos_generados']);
        $stmt->bindParam(':id_dashboard', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Eliminar un registro
    public function delete($id)
    {
        $sql = "DELETE FROM dashboard WHERE id_dashboard = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}