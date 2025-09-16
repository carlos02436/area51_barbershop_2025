<?php
require_once __DIR__ . '/../../controllers/NoticiasController.php';

$controller = new NoticiasController($db);
$id = $_GET['id'] ?? null;

if ($id) {
    $controller->eliminar($id);
}

header("Location: index.php?page=noticias");
exit();