<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/ServicioController.php';

$controller = new ServicioController();
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php?page=servicios');
    exit();
}

// Obtener datos actuales del servicio
$servicio = $controller->mostrarServicio($id);

// BLOQUE PARA ACTUALIZAR SERVICIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imgNombre = $servicio['img_servicio']; // mantener la imagen actual si no se sube nueva
    if (isset($_FILES['img_servicio']) && $_FILES['img_servicio']['error'] === 0) {
        $ext = pathinfo($_FILES['img_servicio']['name'], PATHINFO_EXTENSION);
        $imgNombre = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['img_servicio']['tmp_name'], __DIR__ . '/uploads/' . $imgNombre);
    }

    $controller->actualizarServicio($id, [
        'nombre' => $_POST['nombre'],
        'descripcion' => $_POST['descripcion'],
        'precio' => $_POST['precio'],
        'img_servicio' => $imgNombre,
        'observacion' => $_POST['observacion'] ?? null
    ]);

    header('Location: index.php?page=servicios');
    exit();
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Servicio</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <form action="index.php?page=editar_servicio&id=<?= $servicio['id_servicio'] ?>"
                method="POST"
                enctype="multipart/form-data"
                class="text-white">

                <div class="mb-4">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control form-control-lg w-100"
                        value="<?= htmlspecialchars($servicio['nombre']) ?>" required>
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control form-control-lg w-100" rows="3"><?= htmlspecialchars($servicio['descripcion']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" name="precio" id="precio" class="form-control form-control-lg w-100" step="0.01"
                        value="<?= $servicio['precio'] ?>">
                </div>

                <div class="mb-4">
                    <label for="img_servicio" class="form-label">Imagen</label>
                    <input type="file" name="img_servicio" id="img_servicio" class="form-control form-control-lg w-100">

                    <?php if (!empty($servicio['img_servicio'])): ?>
                        <div class="text-center mt-3">
                            <p class="text-white mb-2">Imagen del servicio actual:</p>
                            <img src="app/uploads/servicios/<?= htmlspecialchars($servicio['img_servicio']) ?>"
                                alt="Imagen servicio"
                                style="max-width:150px; max-height:200px; border: 3px solid #00ff00; border-radius: 8px;">
                        </div>
                    <?php else: ?>
                        <p class="text-warning small mt-2">⚠️ No hay imagen registrada para este servicio.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="observacion" class="form-label">Observación</label>
                    <input type="text" name="observacion" id="observacion" class="form-control form-control-lg w-100"
                        value="<?= htmlspecialchars($servicio['observacion']) ?>">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=servicios" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Actualizar Servicio</button>
                </div>
            </form>
        </div>
    </div>
<main>