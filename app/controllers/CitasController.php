<?php
require_once __DIR__ . '/../../models/Cita.php';
require_once __DIR__ . '/../../models/Cliente.php';
require_once __DIR__ . '/../../models/Servicio.php';
require_once __DIR__ . '/../../models/Barbero.php';

class CitasController {
    private $citaModel;
    private $clienteModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->citaModel = new Cita();
        $this->clienteModel = new Cliente();
    }

    public function create() {
        $servicioModel = new Servicio();
        $barberoModel = new Barbero();

        // Llamar a los métodos que ya existen en tus modelos
        $servicios = method_exists($servicioModel, 'obtenerServicios')
            ? $servicioModel->obtenerServicios()
            : (method_exists($servicioModel, 'getServicios') ? $servicioModel->getServicios() : []);

        $barberos = method_exists($barberoModel, 'obtenerBarberos')
            ? $barberoModel->obtenerBarberos()
            : (method_exists($barberoModel, 'getBarberos') ? $barberoModel->getBarberos() : []);

        require __DIR__ . '/../views/citas/create.php';
    }

    public function store($data) {
        $nombre = trim($data['nombre']);
        $apellido = trim($data['apellido']);

        $cliente = $this->clienteModel->getClientePorNombre($nombre, $apellido);
        $id_cliente = $cliente ? $cliente['id_cliente'] : $this->clienteModel->crearCliente($nombre, $apellido);

        $success = $this->citaModel->crearCita(
            $id_cliente,
            $data['id_barbero'],
            $data['id_servicio'],
            $data['fecha_cita'],
            $data['hora_cita']
        );

        echo json_encode(['success' => $success]);
    }
}