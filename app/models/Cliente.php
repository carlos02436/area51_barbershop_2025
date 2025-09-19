<?php
require_once __DIR__ . '/../../config/database.php';

class Cliente {
    private $db;

    // Permite inyectar PDO desde fuera (opcional) o usar la conexión global definida en config/database.php
    public function __construct($db = null) {
        if ($db instanceof PDO) {
            $this->db = $db;
        } else {
            global $db;
            $this->db = $db;
        }
    }

    public function getClientePorNombre($nombre, $apellido) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE nombre = :nombre AND apellido = :apellido");
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':apellido', $apellido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearCliente($nombre, $apellido, $telefono = null, $correo = null) {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre, apellido, telefono, correo) VALUES (:nombre, :apellido, :telefono, :correo)");
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':apellido', $apellido);
        $stmt->bindValue(':telefono', $telefono);
        $stmt->bindValue(':correo', $correo);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function getClientes() {
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY id_cliente ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por id (útil para la vista editar)
    public function getClientePorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id_cliente = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar cliente
    public function actualizarCliente($id, $nombre, $apellido, $telefono = null, $correo = null) {
        $stmt = $this->db->prepare("UPDATE clientes 
                                    SET nombre = :nombre, apellido = :apellido, telefono = :telefono, correo = :correo 
                                    WHERE id_cliente = :id");
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':apellido', $apellido);
        $stmt->bindValue(':telefono', $telefono);
        $stmt->bindValue(':correo', $correo);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Eliminar cliente
    public function eliminarCliente($id) {
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE id_cliente = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}