<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/VideoController.php';
require_once __DIR__ . '/../../../config/database.php';

// Inicializar el controlador
$controller = new VideoController($db);

// Obtener ID del video a editar
$id = $_GET['id'] ?? null;

// Obtener los datos actuales del video
$video = $controller->ver($id);

if (!$video) {
    die('Video no encontrado');
}

// Procesar formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $link = $_POST['link'] ?? '';
    $publicado_por = $_POST['publicado_por'] ?? $video['publicado_por'];

    // Actualizar video
    $controller->editar($id, $titulo, $link, $publicado_por);

    // Redirigir a la lista de videos
    header('Location: index.php?page=youtube');
    exit;
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h2 class="fw-bold text-white mb-4 text-center">✏️ Editar Video</h2>

        <div class="card text-white mx-auto" style="max-width: 600px; padding: 30px;">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-white">Título</label>
                    <input type="text" name="titulo" class="form-control" 
                           value="<?= htmlspecialchars($video['titulo']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Link del Video</label>
                    <input type="url" name="link" class="form-control" 
                           value="<?= htmlspecialchars($video['link']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Publicado Por</label>
                    <input type="text" name="publicado_por" class="form-control" 
                           value="<?= htmlspecialchars($video['publicado_por']) ?>" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=youtube" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
<main>