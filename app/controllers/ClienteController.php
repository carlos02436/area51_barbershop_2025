<?php
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $model;

    public function __construct() {
        $this->model = new Cliente();
    }

    // Buscar cliente por nombre y apellido
    public function buscarCliente($nombre, $apellido) {
        return $this->model->getClientePorNombre($nombre, $apellido);
    }

    // Crear un nuevo cliente
    public function crearCliente($nombre, $apellido, $telefono = null, $correo = null) {
        return $this->model->crearCliente($nombre, $apellido, $telefono, $correo);
    }

    // Obtener todos los clientes
    public function listarClientes() {
        return $this->model->getClientes();
    }

    // Método para manejar la creación desde un formulario (opcional)
    public function manejarFormularioCrear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $telefono = $_POST['telefono'] ?? null;
            $correo = $_POST['correo'] ?? null;

            // Validar que haya al menos nombre y apellido
            if (empty($nombre) || empty($apellido)) {
                return ['error' => 'Debe ingresar nombre y apellido.'];
            }

            // Revisar si ya existe el cliente
            $clienteExistente = $this->buscarCliente($nombre, $apellido);
            if ($clienteExistente) {
                return ['error' => 'El cliente ya está registrado.', 'cliente' => $clienteExistente];
            }

            // Crear cliente
            $id_cliente = $this->crearCliente($nombre, $apellido, $telefono, $correo);
            return ['success' => 'Cliente creado correctamente.', 'id_cliente' => $id_cliente];
        }
        return null;
    }
}