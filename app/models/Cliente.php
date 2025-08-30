<?php
require_once __DIR__ . '/../../config/database.php';

class Cliente {
    private $db;

    public function __construct() {
        global $db; // Usamos la conexión PDO de database.php
        $this->db = $db;
    }

    // Buscar cliente por nombre y apellido
    public function getClientePorNombre($nombre, $apellido) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE nombre = :nombre AND apellido = :apellido");
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':apellido', $apellido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo cliente
    public function crearCliente($nombre, $apellido, $telefono = null, $correo = null) {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre, apellido, telefono, correo) VALUES (:nombre, :apellido, :telefono, :correo)");
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':apellido', $apellido);
        $stmt->bindValue(':telefono', $telefono);
        $stmt->bindValue(':correo', $correo);
        $stmt->execute();
        return $this->db->lastInsertId(); // devuelve el id_cliente creado
    }

    // Opcional: obtener todos los clientes
    public function getClientes() {
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY id_cliente ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}