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
    try {
        // Validar que el ID sea numérico
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID inválido: $id");
        }

        // Preparar la sentencia
        $sql = "DELETE FROM servicios WHERE id_servicio = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la sentencia
        $stmt->execute();

        // Verificar cuántas filas fueron afectadas
        $filasEliminadas = $stmt->rowCount();

        if ($filasEliminadas > 0) {
            echo "Se eliminó el servicio con ID: $id";
            return true;
        } else {
            echo "No se encontró ningún servicio con ID: $id";
            return false;
        }
    } catch (PDOException $e) {
        // Error de base de datos
        echo "Error PDO: " . $e->getMessage();
        return false;
    } catch (Exception $e) {
        // Otro tipo de error
        echo "Error: " . $e->getMessage();
        return false;
    }
}

}