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
        $this->citaModel = new Cita($db);
        $this->clienteModel = new Cliente();
        $this->servicioModel = new Servicio($db); // requiere constructor con $db
        $this->barberoModel = new Barbero($db);   // requiere constructor con $db
    }

public function index() {
    $citas = $this->citaModel->getCitasConNombres();

    echo '<pre>';
    print_r($citas); // Muestra todo el array de citas
    echo '</pre>';

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