<?php
require_once __DIR__ . '/../../controllers/ClienteController.php';

$controller = new ClienteController();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $controller->eliminarCliente($id);

    // Redirigir al listado de clientes
    header("Location: /area51_barbershop_2025/index.php?page=Cliente");
    exit;
} else {
    echo "ID de cliente no especificado.";
}