<?php
require_once __DIR__ . '/../../../config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php?page=login');
    exit;
}

// Obtener ID del administrador
if (!isset($_GET['id'])) {
    header('Location: administradores.php');
    exit;
}

$id_admin = $_GET['id'];

// Eliminar administrador
$stmt = $db->prepare("DELETE FROM administradores WHERE id_admin = :id_admin");
$stmt->execute([':id_admin' => $id_admin]);

header('Location: administradores.php');
exit;