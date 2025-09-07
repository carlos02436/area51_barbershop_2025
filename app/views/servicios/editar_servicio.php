<?php
require_once __DIR__ . '/../../controllers/ServicioController.php';

$controller = new ServicioController();
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: servicios.php');
    exit();
}

// Obtener datos actuales del servicio
$servicio = $controller->mostrarServicio($id);

// BLOQUE PARA ACTUALIZAR SERVICIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imgNombre = $servicio['img_servicio']; // Mantener imagen actual por defecto

    if (isset($_FILES['img_servicio']) && $_FILES['img_servicio']['error'] === 0) {
        // Eliminar imagen anterior
        if ($imgNombre) {
            $rutaImagen = __DIR__ . '/../../uploads/' . $imgNombre;
            if (file_exists($rutaImagen)) unlink($rutaImagen);
        }

        // Subir nueva imagen
        $ext = pathinfo($_FILES['img_servicio']['name'], PATHINFO_EXTENSION);
        $imgNombre = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['img_servicio']['tmp_name'], __DIR__ . '/../../uploads/' . $imgNombre);
    }

    // Actualizar servicio
    $controller->actualizarServicio($id, [
        'nombre' => $_POST['nombre'],
        'descripcion' => $_POST['descripcion'],
        'precio' => $_POST['precio'],
        'img_servicio' => $imgNombre,
        'observacion' => $_POST['observacion'] ?? null
    ]);

    header('Location: servicios.php');
    exit();
}
?>
<body>
    <div class="container mt-5">
        <h2 class="text-white mb-4">Editar Servicio</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-white">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($servicio['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($servicio['descripcion']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Precio</label>
                <input type="number" name="precio" class="form-control" step="0.01" value="<?= $servicio['precio'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Imagen</label>
                <input type="file" name="img_servicio" class="form-control">
                <?php if($servicio['img_servicio']): ?>
                    <img src="../../uploads/<?= $servicio['img_servicio'] ?>" alt="Imagen actual" style="width:100px; margin-top:10px;">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Observación</label>
                <input type="text" name="observacion" class="form-control" value="<?= htmlspecialchars($servicio['observacion']) ?>">
            </div>
            <button type="submit" class="btn btn-neon">Actualizar Servicio</button>
            <a href="index.php?page=servicios" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
<main>