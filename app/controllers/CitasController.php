<?php
require_once __DIR__ . '/../models/Cita.php';

class CitasController {
    private $db;
    private $model;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Cita($db);
    }

    // Listar y mostrar la vista (puedes incluir la vista desde index.php)
    public function listar() {
        return $this->model->obtenerTodas();
    }

    public function mostrar($id) {
        return $this->model->obtenerPorId($id);
    }

    // Formulario de crear: trae clientes, barberos y servicios
    public function crearFormulario() {
        $clientes = $this->db->query("SELECT id_cliente, nombre, apellido FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
        $barberos = $this->db->query("SELECT id_barbero, nombre FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
        $servicios = $this->db->query("SELECT id_servicio, nombre, img_servicio FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/citas/crear_cita.php';
    }

    // Guardar nueva cita (copia img_servicio desde la tabla servicios)
    public function guardar() {
        // Validaciones básicas
        $id_cliente = $_POST['id_cliente'] ?? null;
        $id_barbero = $_POST['id_barbero'] ?? null;
        $id_servicio = $_POST['id_servicio'] ?? null;
        $fecha = $_POST['fecha_cita'] ?? null;
        $hora = $_POST['hora_cita'] ?? null;
        $estado = $_POST['estado'] ?? 'pendiente';

        if (!$id_barbero || !$id_servicio || !$fecha || !$hora) {
            header('Location: index.php?page=crear_cita&error=Datos incompletos');
            exit;
        }

        // Obtener img_servicio desde tabla servicios
        $stmt = $this->db->prepare("SELECT img_servicio FROM servicios WHERE id_servicio = :id LIMIT 1");
        $stmt->execute([':id' => $id_servicio]);
        $svc = $stmt->fetch(PDO::FETCH_ASSOC);
        $imgServicio = $svc['img_servicio'] ?? null;

        $datos = [
            'id_cliente' => $id_cliente ?: null,
            'id_barbero' => $id_barbero,
            'id_servicio' => $id_servicio,
            'img_servicio' => $imgServicio,
            'fecha_cita' => $fecha,
            'hora_cita' => $hora,
            'estado' => $estado
        ];

        $this->model->crear($datos);

        header('Location: index.php?page=citas');
        exit;
    }

    // Formulario de editar
    public function editarFormulario($id) {
        $cita = $this->model->obtenerPorId($id);
        if (!$cita) {
            header('Location: index.php?page=citas');
            exit;
        }

        $clientes = $this->db->query("SELECT id_cliente, nombre, apellido FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
        $barberos = $this->db->query("SELECT id_barbero, nombre FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
        $servicios = $this->db->query("SELECT id_servicio, nombre, img_servicio FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/citas/editar_cita.php';
    }

    // Actualizar cita (si se cambia servicio, actualizar img_servicio)
    public function actualizar($id) {
        $id_cliente = $_POST['id_cliente'] ?? null;
        $id_barbero = $_POST['id_barbero'] ?? null;
        $id_servicio = $_POST['id_servicio'] ?? null;
        $fecha       = $_POST['fecha_cita'] ?? null;
        $hora        = $_POST['hora_cita'] ?? null;
        $estado      = $_POST['estado'] ?? 'pendiente';

        // Validar datos obligatorios
        if (!$id_barbero || !$id_servicio || !$fecha || !$hora) {
            header("Location: index.php?page=editar_cita&id={$id}&error=Datos incompletos");
            exit;
        }

        // Obtener datos actuales de la cita
        $citaExistente = $this->model->obtenerPorId($id);
        if (!$citaExistente) {
            header("Location: index.php?page=citas&error=Cita no encontrada");
            exit;
        }

        // Obtener img_servicio del servicio (si cambia)
        $imgServicio = $citaExistente['img_servicio']; // valor actual
        if ($id_servicio != $citaExistente['id_servicio']) {
            $stmt = $this->db->prepare("SELECT img_servicio FROM servicios WHERE id_servicio = :id LIMIT 1");
            $stmt->execute([':id' => $id_servicio]);
            $svc = $stmt->fetch(PDO::FETCH_ASSOC);
            $imgServicio = $svc['img_servicio'] ?? $imgServicio;
        }

        // Preparar datos a actualizar
        $datos = [
            'id_cliente'   => $id_cliente ?: null,
            'id_barbero'   => $id_barbero,
            'id_servicio'  => $id_servicio,
            'img_servicio' => $imgServicio,
            'fecha_cita'   => $fecha,
            'hora_cita'    => $hora,
            'estado'       => $estado
        ];

        try {
            $this->model->actualizar($id, $datos);
            header('Location: index.php?page=citas&success=1');
            exit;
        } catch (Exception $e) {
            // Captura error SQL
            header("Location: index.php?page=editar_cita&id={$id}&error=" . urlencode($e->getMessage()));
            exit;
        }
    }

    // Eliminar cita
    public function eliminar($id) {
        $this->model->eliminar($id);
        header('Location: index.php?page=citas');
        exit;
    }
}