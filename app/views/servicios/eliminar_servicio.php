<?php
require_once __DIR__ . '/../../controllers/ServicioController.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $controller = new ServicioController();

    // Eliminar la imagen si existe
    $servicio = $controller->mostrarServicio($id);
    if ($servicio && $servicio['img_servicio']) {
        $rutaImagen = __DIR__ . '/../../uploads/' . $servicio['img_servicio'];
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    // Eliminar registro en BD
    $controller->eliminarServicio($id);
}

header('Location: servicios.php');
exit();