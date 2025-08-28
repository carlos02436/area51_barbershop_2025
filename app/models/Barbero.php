<?php
// app/models/Barbero.php

class Barbero {
    private $db;

    // Recibe la conexión PDO desde database.php
    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener todos los barberos
    public function obtenerBarberos() {
        $query = $this->db->query("SELECT * FROM barberos ORDER BY fecha_contratacion ASC");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un barbero por su ID
    public function obtenerBarberoPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM barberos WHERE id_barbero = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}