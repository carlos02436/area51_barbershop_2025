<?php

// CONFIGURACIÓN DE LA CONEXIÓN
$host = "localhost";
$user = "root";
$password = "";
$dbname = "area51_barberia";

// Crear conexión
$conn = @new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// INCLUIR MODELS
require_once __DIR__ . '/app/models/Cita.php';
$citaModel = new Cita(); // Instancia del modelo de citas

// DEFINIR LA PÁGINA ACTUAL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// INCLUIR HEADER
include __DIR__ . '/app/views/plantillas/header.php';

// ENRUTADOR
switch ($page) {
    
    case 'home':
        include __DIR__ . '/app/views/home.php';
        break;

    case 'panel':
        include __DIR__ . '/app/views/citas/panel.php';
        break;

    case 'create':
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

// INCLUIR FOOTER
include __DIR__ . '/app/views/plantillas/footer.php';