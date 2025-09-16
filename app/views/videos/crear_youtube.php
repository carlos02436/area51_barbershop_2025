<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../controllers/VideoController.php';
require_once __DIR__ . '/../../../config/database.php';

$controller = new VideoController($db);

// Nombre del admin logueado
$nombreAdmin = $_SESSION['admin_nombre'] ?? 'Admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $link = $_POST['link'] ?? '';
    $publicado_por = $nombreAdmin; // Tomar el nombre del admin

    // Crear el video
    $controller->crear($titulo, $link, $publicado_por);

    // Redirigir a la página de videos
    header('Location: index.php?page=youtube');
    exit;
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Publicar nuevo video</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 30px;">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-white">Título</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Link del Video</label>
                    <input type="url" name="link" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Publicado Por</label>
                    <input type="text" name="publicado_por" class="form-control" 
                        value="<?= htmlspecialchars($nombreAdmin) ?>" readonly>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=youtube" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Publicar</button>
                </div>
            </form>
        </div>
    </div>
<main>