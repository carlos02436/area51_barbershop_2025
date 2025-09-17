<?php
require_once __DIR__ . '/../../controllers/CitasController.php';
require_once __DIR__ . '/../../config/database.php';

$controller = new CitasController($db);

$id = $_GET['id'] ?? null;
if ($id) {
    $controller->cancelarCita($id);
}

header('Location: index.php?page=citas');
exit();