<?php
require_once __DIR__ . '/../../config/database.php';

class Barbero {
    private $db;

    public function __construct() {
        global $db; 
        $this->db = $db;
    }

    // Obtener todos los barberos
    public function obtenerTodos() {
        $sql = "SELECT id_barbero, img_barberos, nombre, especialidad, telefono, email, fecha_contratacion 
                FROM barberos 
                ORDER BY id_barbero ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener barbero por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM barberos WHERE id_barbero = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear barbero
    public function crear($data) {
        $sql = "INSERT INTO barberos (img_barberos, nombre, especialidad, telefono, email, fecha_contratacion) 
                VALUES (:img_barberos, :nombre, :especialidad, :telefono, :email, :fecha_contratacion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':img_barberos' => $data['img_barberos'],
            ':nombre' => $data['nombre'],
            ':especialidad' => $data['especialidad'],
            ':telefono' => $data['telefono'],
            ':email' => $data['email'],
            ':fecha_contratacion' => $data['fecha_contratacion']
        ]);
    }

    // Actualizar barbero
    public function actualizar($id, $data) {
        $sql = "UPDATE barberos SET img_barberos = :img_barberos, nombre = :nombre, especialidad = :especialidad, 
                telefono = :telefono, email = :email, fecha_contratacion = :fecha_contratacion
                WHERE id_barbero = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':img_barberos' => $data['img_barberos'],
            ':nombre' => $data['nombre'],
            ':especialidad' => $data['especialidad'],
            ':telefono' => $data['telefono'],
            ':email' => $data['email'],
            ':fecha_contratacion' => $data['fecha_contratacion'],
            ':id' => $id
        ]);
    }

    // Eliminar barbero
    public function eliminar($id) {
        $sql = "DELETE FROM barberos WHERE id_barbero = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}