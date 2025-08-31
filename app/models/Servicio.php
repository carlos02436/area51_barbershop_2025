<?php
require_once __DIR__ . '/../../config/database.php';

class Servicio {
    private $db;

    public function __construct() {
        global $db; // Conexión PDO desde database.php
        $this->db = $db;
    }

    // 🔹 Listar todos los servicios con toda la información
    public function obtenerServicios() {
        $sql = "SELECT id_servicio, img_servicio, nombre, descripcion, precio, observacion 
                FROM servicios";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Obtener un solo servicio por ID
    public function obtenerServicioPorId($id) {
        $sql = "SELECT * FROM servicios WHERE id_servicio = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Crear nuevo servicio
    public function crearServicio($data) {
        $sql = "INSERT INTO servicios (img_servicio, nombre, descripcion, precio, observacion) 
                VALUES (:img_servicio, :nombre, :descripcion, :precio, :observacion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':img_servicio' => $data['img_servicio'] ?? null,
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'] ?? null,
            ':precio' => $data['precio'],
            ':observacion' => $data['observacion'] ?? null
        ]);
    }

    // 🔹 Actualizar servicio
    public function actualizarServicio($id, $data) {
        $sql = "UPDATE servicios 
                SET img_servicio = :img_servicio, nombre = :nombre, descripcion = :descripcion, 
                    precio = :precio, observacion = :observacion 
                WHERE id_servicio = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':img_servicio' => $data['img_servicio'] ?? null,
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'] ?? null,
            ':precio' => $data['precio'],
            ':observacion' => $data['observacion'] ?? null,
            ':id' => $id
        ]);
    }

    // 🔹 Eliminar servicio
    public function eliminarServicio($id) {
        $sql = "DELETE FROM servicios WHERE id_servicio = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}