<?php
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $model;

    // Recibe opcionalmente la conexión PDO para facilitar pruebas / consistencia
    public function __construct($db = null) {
        $this->model = new Cliente($db);
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

    // Obtener cliente por id
    public function obtenerClientePorId($id) {
        return $this->model->getClientePorId($id);
    }

    // Método para manejar la creación desde un formulario (opcional)
    public function manejarFormularioCrear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['editar_id'])) {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $telefono = $_POST['telefono'] ?? null;
            $correo = $_POST['correo'] ?? null;

            if (empty($nombre) || empty($apellido)) {
                return ['error' => 'Debe ingresar nombre y apellido.'];
            }

            $clienteExistente = $this->buscarCliente($nombre, $apellido);
            if ($clienteExistente) {
                return ['error' => 'El cliente ya está registrado.', 'cliente' => $clienteExistente];
            }

            $id_cliente = $this->crearCliente($nombre, $apellido, $telefono, $correo);
            return ['success' => 'Cliente creado correctamente.', 'id_cliente' => $id_cliente];
        }
        return null;
    }

    // Actualizar cliente (delegado al modelo)
    public function actualizarCliente($id, $nombre, $apellido, $telefono = null, $correo = null) {
        return $this->model->actualizarCliente($id, $nombre, $apellido, $telefono, $correo);
    }

    // Eliminar cliente (delegado al modelo)
    public function eliminarCliente($id) {
        return $this->model->eliminarCliente($id);
    }
}