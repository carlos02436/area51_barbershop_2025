<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/TikTokController.php';
require_once __DIR__ . '/../../../config/database.php';

$controller = new TikTokController($db); // Usar TikTokController

$nombreAdmin = $_SESSION['admin_nombre'] ?? 'Admin';

// Función para extraer el video_id desde la URL
function extraerVideoID($url) {
    if (preg_match('/video\/(\d+)/', $url, $matches)) {
        return $matches[1];
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url          = $_POST['url'] ?? '';
    $video_id     = extraerVideoID($url); // extraer automáticamente
    $descripcion  = $_POST['descripcion'] ?? '';
    $publicado_por= $nombreAdmin;

    $controller->crear($url, $video_id, $descripcion);

    header('Location: index.php?page=tiktok');
    exit;
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Publicar nuevo video</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 30px;">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-white">URL del Video</label>
                    <input type="url" name="url" class="form-control" required>
                </div>
                <!-- video_id eliminado del formulario -->

                <div class="mb-3">
                    <label class="form-label text-white">Descripción</label>
                    <textarea name="descripcion" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Publicado por</label>
                    <input type="text" name="publicado_por" class="form-control" value="<?= htmlspecialchars($nombreAdmin) ?>" readonly>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=tiktok" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Publicar</button>
                </div>
            </form>
        </div>
    </div>
<main>