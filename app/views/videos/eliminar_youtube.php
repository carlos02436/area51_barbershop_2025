<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../controllers/VideoController.php';
require_once __DIR__ . '/../../../config/database.php';

$controller = new VideoController($db);
$id = $_GET['id'] ?? null;

if ($id) {
    $controller->eliminar($id);
}

// Redirigir a la lista de videos
header('Location: index.php?page=youtube');
exit;