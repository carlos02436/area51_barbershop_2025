<?php
require_once __DIR__ . '/../../controllers/NoticiasController.php';
if (!isset($db)) {
    require_once __DIR__ . '/../../config/database.php';
}

$controller = new NoticiasController($db);
$id = $_GET['id'] ?? null;

if ($id) {
    $controller->eliminar($id);
}

header("Location: index.php?page=noticias");
exit();