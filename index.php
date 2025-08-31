<?php
// index.php

// Iniciar sesión solo una vez
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==================== CONEXIÓN A LA BD ====================
require_once __DIR__ . '/config/database.php'; // Aquí tienes $db (PDO)

// ==================== AUTOLOAD MODELOS Y CONTROLADORES ====================
require_once __DIR__ . '/app/models/Administrador.php';
require_once __DIR__ . '/app/models/Cita.php';
require_once __DIR__ . '/app/models/Servicio.php';
require_once __DIR__ . '/app/models/Barbero.php';
require_once __DIR__ . '/app/models/Dashboard.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';

// ==================== INSTANCIAR MODELOS NECESARIOS ====================
$barberoModel = new Barbero();
$servicioModel = new Servicio();

// ==================== DEFINIR PÁGINA ====================
$page = $_GET['page'] ?? 'home';

// ==================== HEADER ====================
include __DIR__ . '/app/views/plantillas/header.php';

// ==================== ENRUTADOR PRINCIPAL ====================
switch ($page) {
    case 'home':
        include __DIR__ . '/app/views/home.php';
        break;

    case 'login':
        include __DIR__ . '/app/views/login.php';
        break;
        
    case 'panel':
        include __DIR__ . '/app/views/panel.php';
        break;

    case 'dashboard':
        include __DIR__ . '/app/views/dashboard.php';
        break;

    case 'create':
        $servicios = $servicioModel->getServicios();
        $barberos = $barberoModel->obtenerBarberos();
        include __DIR__ . '/app/views/citas/create.php';
        break;

    case 'edit':
        include __DIR__ . '/app/views/citas/edit.php';
        break;

    case 'delete':
        include __DIR__ . '/app/views/citas/delete.php';
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