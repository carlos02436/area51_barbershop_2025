<?php
require_once __DIR__ . '/../models/Cita.php';

class PanelController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: /area51_barbershop_2025/login");
            exit;
        }

        // Si quieres mostrar citas en el panel
        $citaModel = new Cita($this->db);
        $citas = $citaModel->getAll();

        require __DIR__ . '/../views/panel.php';
    }
}
