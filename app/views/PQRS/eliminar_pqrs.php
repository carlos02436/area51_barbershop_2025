<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/PQRSController.php';

$controller = new PQRSController($db);
$controller->eliminar($_GET['id']);
header("Location: pqrs.php");
exit;