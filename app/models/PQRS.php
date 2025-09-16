<?php
class PQRS {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function listar() {
        $stmt = $this->db->prepare("SELECT * FROM pqrs ORDER BY fecha_envio DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ver($id) {
        $stmt = $this->db->prepare("SELECT * FROM pqrs WHERE id_pqrs = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $apellidos, $email, $tipo, $mensaje) {
        $stmt = $this->db->prepare("INSERT INTO pqrs (nombre, apellidos, email, tipo, mensaje) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre, $apellidos, $email, $tipo, $mensaje]);
    }

    public function editar($id, $nombre, $apellidos, $email, $tipo, $mensaje, $estado) {
        $stmt = $this->db->prepare("UPDATE pqrs SET nombre = ?, apellidos = ?, email = ?, tipo = ?, mensaje = ?, estado = ? WHERE id_pqrs = ?");
        return $stmt->execute([$nombre, $apellidos, $email, $tipo, $mensaje, $estado, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("UPDATE pqrs SET estado = 'Resuelto' WHERE id_pqrs = ?");
        return $stmt->execute([$id]);
    }
}