<?php
require_once __DIR__ . '/../models/Cita.php';

class CitasController {
    private $db;
    private $model;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Cita($db);
    }

    // Listar y mostrar la vista
    public function listar() {
        return $this->model->obtenerTodas();
    }

    public function mostrar($id) {
        return $this->model->obtenerPorId($id);
    }

    // Formulario de crear
    public function crearFormulario() {
        $clientes = $this->db->query("SELECT id_cliente, nombre, apellido FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
        $barberos = $this->db->query("SELECT id_barbero, nombre FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
        $servicios = $this->db->query("SELECT id_servicio, nombre, img_servicio FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/citas/crear_cita.php';
    }

    // Guardar nueva cita (con consultas preparadas)
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_cliente   = $_POST['id_cliente'] ?? null;
            $id_barbero   = $_POST['id_barbero'] ?? null;
            $id_servicio  = $_POST['id_servicio'] ?? null;
            $fecha_cita   = $_POST['fecha_cita'] ?? null;
            $hora_cita    = $_POST['hora_cita'] ?? null;
            $estado       = $_POST['estado'] ?? 'pendiente';
            $img_servicio = $_POST['img_servicio'] ?? null; 

            if ($id_cliente && $id_barbero && $id_servicio && $fecha_cita && $hora_cita) {

                // 1️⃣ Guardar la cita en la base de datos
                $sql = "INSERT INTO citas (id_cliente, id_barbero, id_servicio, fecha_cita, hora_cita, estado, img_servicio)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $id_cliente,
                    $id_barbero,
                    $id_servicio,
                    $fecha_cita,
                    $hora_cita,
                    $estado,
                    $img_servicio
                ]);

                // 2️⃣ Obtener datos del cliente
                $stmtCliente = $this->db->prepare("SELECT nombre, telefono FROM clientes WHERE id_cliente = ?");
                $stmtCliente->execute([$id_cliente]);
                $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

                // 3️⃣ Obtener datos del barbero
                $stmtBarbero = $this->db->prepare("SELECT nombre, telefono FROM barberos WHERE id_barbero = ?");
                $stmtBarbero->execute([$id_barbero]);
                $barbero = $stmtBarbero->fetch(PDO::FETCH_ASSOC);

                // 4️⃣ Obtener datos del servicio
                $stmtServicio = $this->db->prepare("SELECT nombre FROM servicios WHERE id_servicio = ?");
                $stmtServicio->execute([$id_servicio]);
                $servicio = $stmtServicio->fetch(PDO::FETCH_ASSOC);

                // 5️⃣ Preparar los mensajes de WhatsApp
                $mensaje_cliente = urlencode(
                    "Hola {$cliente['nombre']}, tu cita para el servicio '{$servicio['nombre']}' en ÁREA 51 BARBER SHOP ha sido confirmada para el {$fecha_cita} a las {$hora_cita}."
                );
                $mensaje_barbero = urlencode(
                    "Hola {$barbero['nombre']}, tienes una nueva cita para el servicio '{$servicio['nombre']}' con {$cliente['nombre']} el {$fecha_cita} a las {$hora_cita}."
                );

                $telefono_cliente = $cliente['telefono'];
                $telefono_barbero = $barbero['telefono'];

                try {
                    file_get_contents("https://api.whatsapp.com/send?phone={$telefono_cliente}&text={$mensaje_cliente}");
                    file_get_contents("https://api.whatsapp.com/send?phone={$telefono_barbero}&text={$mensaje_barbero}");
                } catch (\Exception $e) {
                    // Registrar error opcionalmente
                }

                // 6️⃣ Redirigir con éxito
                header("Location: index.php?page=home#contacto");
                exit;

            } else {
                // Redirigir con error si faltan datos
                header("Location: index.php?page=crear_cita&error=1");
                exit;
            }
        }
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

    // Actualizar cita
    public function actualizar() {
        $id = $_POST['id_cita'] ?? null;
        $id_cliente = $_POST['id_cliente'] ?? null;
        $id_barbero = $_POST['id_barbero'] ?? null;
        $id_servicio = $_POST['id_servicio'] ?? null;
        $fecha       = $_POST['fecha_cita'] ?? null;
        $hora        = $_POST['hora_cita'] ?? null;
        $estado      = $_POST['estado'] ?? 'pendiente';
        $imgServicio = $_POST['img_servicio'] ?? null;

        if (!$id || !$id_barbero || !$id_servicio || !$fecha || !$hora) {
            header("Location: index.php?page=editar_cita&id={$id}&error=Datos incompletos");
            exit;
        }

        $datos = [
            'id_cliente'   => $id_cliente,
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