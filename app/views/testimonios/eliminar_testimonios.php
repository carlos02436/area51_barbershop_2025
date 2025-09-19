<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/TestimoniosController.php';
if (!isset($db)) {
    require_once __DIR__ . '/../../config/database.php';
}

$controller = new TestimoniosController();

$id = $_GET['id'] ?? null;

if ($id) {
    $controller->eliminar($id);
}

header("Location: index.php?page=testimonios");
exit();