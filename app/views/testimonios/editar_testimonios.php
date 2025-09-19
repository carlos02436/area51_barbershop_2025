<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/TestimoniosController.php';
if (!isset($db)) {
    require_once __DIR__ . '/../../config/database.php';
}

$controller = new TestimoniosController();

// Obtener el ID del testimonio
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php?page=testimonios");
    exit();
}

// Obtener el testimonio
$registro = $controller->obtenerPorId($id);
if (!$registro) {
    header("Location: index.php?page=testimonios");
    exit();
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $img = !empty($_FILES['img']['name']) ? $_FILES['img']['name'] : $registro['img'];

    if (!empty($_FILES['img']['name'])) {
        $rutaDestino = __DIR__ . '/../../uploads/testimonios/' . $img;
        move_uploaded_file($_FILES['img']['tmp_name'], $rutaDestino);
    }

    $controller->editar($id, $_POST['nombre'], $_POST['mensaje'], $img);
    header("Location: index.php?page=testimonios");
    exit();
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Testimonio</h1>
        <div class="card text-white mx-auto" style="max-width:600px;padding:40px;">
            <form action="index.php?page=editar_testimonios&id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required value="<?= htmlspecialchars($registro['nombre']) ?>">
                </div>

                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea name="mensaje" id="mensaje" class="form-control" rows="5" required><?= htmlspecialchars($registro['mensaje']) ?></textarea>
                </div>

                <div class="mb-3 text-center">
                    <label for="img" class="form-label">Imagen Testimonio</label>
                    <?php if (!empty($registro['img'])): ?>
                        <div class="mb-2">
                            <img src="app/uploads/testimonios/<?= htmlspecialchars($registro['img']) ?>"
                             class="img-thumbnail border-success"
                             style="max-width: 100px; max-height: 180px; border: 2px solid #28a745;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="img" id="img" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=testimonios" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
<main>