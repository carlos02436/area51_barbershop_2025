<?php
// eliminar_tiktok.php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/TikTokController.php';
require_once __DIR__ . '/../../../config/database.php';

// Verificar que se recibió el id
$id = $_GET['id'] ?? null;

if (!$id) {
    // Si no hay id, redirigir a la lista
    header('Location: index.php?page=tiktok');
    exit;
}

// Instanciar el controlador
$controller = new TikTokController($db);

try {
    // Eliminar lógicamente el TikTok
    $controller->eliminar($id);
} catch (Exception $e) {
    // Aquí podrías manejar errores, loguearlos o mostrar mensaje
    // Por simplicidad redirigimos igual
}

// Redirigir a la página de lista de TikToks
header('Location: index.php?page=tiktok');
exit;