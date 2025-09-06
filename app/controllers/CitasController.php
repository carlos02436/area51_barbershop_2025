<?php
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Servicio.php';
require_once __DIR__ . '/../models/Barbero.php';

class CitasController {
    private $citaModel;
    private $clienteModel;
    private $servicioModel;
    private $barberoModel;

    public function __construct($db) {
        $this->citaModel = new Cita($db); // sí necesita $db
        $this->clienteModel = new Cliente(); // quitar $db
        $this->servicioModel = new Servicio(); // quitar $db
        $this->barberoModel = new Barbero(); // quitar $db
    }

    public function index() {
        $citas = $this->citaModel->getCitasConNombres();
        if (!$citas) {
            $citas = []; // proteger foreach en la vista
        }
        require __DIR__ . '/../views/citas/citas.php';
    }

    public function create() {
        $servicios = $this->servicioModel->getServicios();
        $barberos = $this->barberoModel->getBarberos();
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