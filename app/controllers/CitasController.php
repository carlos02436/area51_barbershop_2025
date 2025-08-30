<?php
require_once __DIR__ . '/../../models/Cita.php';
require_once __DIR__ . '/../../models/Cliente.php';

$citaModel = new Cita();
$clienteModel = new Cliente();

$action = $_GET['action'] ?? '';

$data = json_decode(file_get_contents("php://input"), true);

switch($action) {

    case 'crear':
        $nombre = trim($data['nombre']);
        $apellido = trim($data['apellido']);

        // Revisar si el cliente ya existe
        $cliente = $clienteModel->getClientePorNombre($nombre, $apellido);

        if (!$cliente) {
            // Si no existe, lo creamos
            $id_cliente = $clienteModel->crearCliente($nombre, $apellido);
        } else {
            $id_cliente = $cliente['id_cliente'];
        }

        // Crear cita
        $success = $citaModel->crearCita(
            $id_cliente,
            $data['id_barbero'],
            $data['id_servicio'],
            $data['fecha_cita'],
            $data['hora_cita']
        );

        echo json_encode(['success' => $success]);
        break;

    case 'editar':
        $success = $citaModel->editarCita(
            $data['id_cita'],
            $data['id_cliente'],
            $data['id_barbero'],
            $data['id_servicio'],
            $data['fecha_cita'],
            $data['hora_cita'],
            $data['estado']
        );
        echo json_encode(['success' => $success]);
        break;

    case 'cancelar':
        $success = $citaModel->cancelarCita($data['id_cita']);
        echo json_encode(['success' => $success]);
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}
