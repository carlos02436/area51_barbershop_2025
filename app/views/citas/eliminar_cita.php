<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../controllers/CitasController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$citasController = new CitasController($db);

$id = $_GET['id'] ?? null;

if ($id) {
    $citasController->eliminarCita($id);
}

header("Location: index.php?page=citas");
exit;