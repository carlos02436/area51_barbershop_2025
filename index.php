<?php
session_start();
require_once __DIR__ . '/config/database.php'; // Conexión PDO

// ==================== AUTOLOAD MODELOS ====================
require_once __DIR__ . '/app/models/Administrador.php';
require_once __DIR__ . '/app/models/Cita.php';
require_once __DIR__ . '/app/models/Servicio.php';
require_once __DIR__ . '/app/models/Barbero.php';
require_once __DIR__ . '/app/models/Dashboard.php';

$barberoModel = new Barbero();
$servicioModel = new Servicio();

// ==================== DEFINIR PÁGINA ====================
$page = $_GET['page'] ?? 'login'; // Por defecto 'login'

// ==================== HEADER ====================
include __DIR__ . '/app/views/plantillas/header.php';

// ==================== ENRUTADOR ====================
switch ($page) {

    case 'panel':
        require __DIR__ . '/app/views/panel.php';
        break;

    case 'login':
        require __DIR__ . '/app/views/login.php';
        break;

    case 'dashboard':
        require __DIR__ . '/app/views/dashboard.php';
        break;

    case 'create':
        require __DIR__ . '/app/views/citas/create.php';
        break;

    case 'edit':
        require __DIR__ . '/app/views/citas/edit.php';
        break;

    case 'delete':
        include __DIR__ . '/app/views/citas/delete.php';
        break;

    case 'home':
        include __DIR__ . '/app/views/home.php';
        break;

    default:
        echo "<section class='container py-5 text-center'>
                <h2 class='text-danger'>404 - Página no encontrada</h2>
                <p>Lo sentimos, la página que buscas no existe.</p>
                <a href='index.php' class='btn btn-primary mt-3'>Volver al inicio</a>
              </section>";
        break;
}
// ==================== FOOTER ====================
include __DIR__ . '/app/views/plantillas/footer.php';