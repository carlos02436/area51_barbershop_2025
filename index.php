<?php
session_start();
require_once __DIR__ . '/config/database.php'; // Conexión PDO

// ==================== AUTOLOAD MODELOS ====================
require_once __DIR__ . '/app/models/Administrador.php';
require_once __DIR__ . '/app/models/Cita.php';
require_once __DIR__ . '/app/models/Servicio.php';
require_once __DIR__ . '/app/models/Barbero.php';
require_once __DIR__ . '/app/models/Dashboard.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';

$barberoModel = new Barbero();
$servicioModel = new Servicio();

// ==================== DEFINIR PÁGINA ====================
$page = $_GET['page'] ?? 'login'; // Por defecto 'login'

// ==================== HEADER ====================
include __DIR__ . '/app/views/plantillas/header.php';

// ==================== ENRUTADOR ====================
switch ($page) {

    case 'login':
        include __DIR__ . '/app/views/login.php';
        break;

    case 'panel':
        include __DIR__ . '/app/views/panel.php';
        break;

    // Ejemplo de otras páginas que requieren sesión
    case 'dashboard':
    case 'create':
    case 'edit':
    case 'delete':
        if (!isset($_SESSION['admin'])) {
            header("Location: index.php?page=login");
            exit;
        }
        include __DIR__ . '/app/views/' . $page . '.php';
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