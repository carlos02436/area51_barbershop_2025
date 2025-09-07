<?php
require_once __DIR__ . '/../models/Cita.php';

class CitasController {
    private $db;
    private $citaModel;

    public function __construct($db) {
        $this->db = $db;
        $this->citaModel = new Cita($db);
    }

    // Listar todas las citas
    public function index() {
        $citas = $this->citaModel->obtenerTodas();
        require __DIR__ . '/../views/citas/citas.php';
    }

    public function listar() {
        $sql = "
            SELECT 
                c.id_cita,
                cl.nombre AS nombre_cliente,
                COALESCE(cl.apellido, '') AS apellido_cliente,
                b.nombre AS nombre_barbero,
                s.nombre AS nombre_servicio,
                c.fecha_cita,
                c.hora_cita,
                c.estado
            FROM citas c
            LEFT JOIN clientes cl ON c.id_cliente = cl.id_cliente
            LEFT JOIN barberos b ON c.id_barbero = b.id_barbero
            LEFT JOIN servicios s ON c.id_servicio = s.id_servicio
            ORDER BY c.fecha_cita ASC, c.hora_cita ASC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mostrar formulario de creación
    public function crearFormulario() {
        // Obtener datos relacionados
        $clientes = $this->db->query("SELECT id_cliente, nombre, apellido FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
        $barberos = $this->db->query("SELECT id_barbero, nombre FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
        $servicios = $this->db->query("SELECT id_servicio, nombre FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/citas/crear.php';
    }

    public function editarFormulario($id) {
        $cita = $this->citaModel->obtenerPorId($id);

        // Concatenar nombre y apellido del cliente
        $clientes = $this->db->query("
            SELECT id_cliente, CONCAT(nombre, ' ', apellido) AS nombre_completo 
            FROM clientes
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Concatenar nombre y apellido del barbero (opcional)
        $barberos = $this->db->query("SELECT id_barbero, nombre FROM barberos")->fetchAll(PDO::FETCH_ASSOC);

        $servicios = $this->db->query("
            SELECT id_servicio, nombre 
            FROM servicios
        ")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/citas/editar.php';
    }

    // Actualizar cita
    public function actualizar($id, $datos) {
        $this->citaModel->actualizar($id, $datos);
        header('Location: index.php?page=citas');
    }

    // Eliminar cita
    public function eliminar($id) {
        $this->citaModel->eliminar($id);
        header('Location: index.php?page=citas');
    }

    // 🔥 Nuevo método: Guardar cita y cliente nuevo
    public function guardar() {
        $tipo_cliente = $_POST['tipo_cliente'] ?? 'registrado';
        $id_cliente = $_POST['id_cliente'] ?? '';
        $nuevo_nombre = trim($_POST['nuevo_nombre'] ?? '');
        $nuevo_apellido = trim($_POST['nuevo_apellido'] ?? '');
        $nuevo_telefono = trim($_POST['nuevo_telefono'] ?? '');
        $nuevo_correo = trim($_POST['nuevo_correo'] ?? '');

        $id_barbero = $_POST['id_barbero'] ?? null;
        $id_servicio = $_POST['id_servicio'] ?? null;
        $fecha = $_POST['fecha_cita'] ?? null;
        $hora = $_POST['hora_cita'] ?? null;
        $estado = $_POST['estado'] ?? 'pendiente';

        if (!$id_barbero || !$id_servicio || !$fecha || !$hora) {
            header("Location: index.php?page=citas&error=Datos incompletos");
            exit;
        }

        // Iniciar transacción para evitar duplicados
        $this->db->beginTransaction();

        try {
            // Si es cliente nuevo, verificar que no exista
            if ($tipo_cliente === 'nuevo') {
                $check = $this->db->prepare("SELECT id_cliente FROM clientes WHERE nombre = ? AND apellido = ? AND telefono = ?");
                $check->execute([$nuevo_nombre, $nuevo_apellido, $nuevo_telefono]);
                $cliente_existente = $check->fetch(PDO::FETCH_ASSOC);

                if ($cliente_existente) {
                    $id_cliente = $cliente_existente['id_cliente'];
                } else {
                    $stmt = $this->db->prepare("INSERT INTO clientes (nombre, apellido, telefono, correo, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->execute([$nuevo_nombre, $nuevo_apellido, $nuevo_telefono, $nuevo_correo]);
                    $id_cliente = $this->db->lastInsertId();
                }
            }

            // Insertar cita
            $stmt = $this->db->prepare("
                INSERT INTO citas (id_cliente, id_barbero, id_servicio, fecha_cita, hora_cita, estado)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id_cliente, $id_barbero, $id_servicio, $fecha, $hora, $estado]);

            $this->db->commit();

            // Redirección tipo PRG (Post-Redirect-Get) para evitar doble envío
            header("Location: index.php?page=citas&success=1");
            exit;

        } catch (Exception $e) {
            $this->db->rollBack();
            header("Location: index.php?page=citas&error=Error al guardar");
            exit;
        }
    }
}