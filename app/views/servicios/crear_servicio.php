<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/ServicioController.php';

$controller = new ServicioController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manejo de la imagen
    $imgNombre = null;
    if (isset($_FILES['img_servicio']) && $_FILES['img_servicio']['error'] === 0) {
        $ext = pathinfo($_FILES['img_servicio']['name'], PATHINFO_EXTENSION);
        $imgNombre = uniqid() . '.' . $ext; // Nombre único
        move_uploaded_file($_FILES['img_servicio']['tmp_name'], __DIR__ . '/../../uploads/' . $imgNombre);
    }

    // Crear servicio
    $controller->crearServicio([
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
<div class="container py-5" style="margin-top:100px;">
    <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Nuevo Servicio</h1>
    <div class="card text-white mx-auto" style="max-width: 600px; padding: 30px;">
        <form method="POST" enctype="multipart/form-data" class="needs-validation">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control border-secondary" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control border-secondary" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input type="number" name="precio" class="form-control border-secondary" step="0.01">
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input type="file" name="img_servicio" class="form-control border-secondary" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Observación</label>
                <input type="text" name="observacion" class="form-control border-secondary">
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php?page=servicios" class="btn btn-danger" style="width:130px;">Cancelar</a>
                <button type="submit" class="btn btn-neon" style="width:130px;">Crear Servicio</button>
            </div>
        </form>
    </div>
</div>
<main>
