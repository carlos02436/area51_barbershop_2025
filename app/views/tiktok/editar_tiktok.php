<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../controllers/TiktokController.php';
require_once __DIR__ . '/../../../config/database.php';

// Inicializar el controlador
$controller = new TiktokController($db);

// Obtener nombre del admin en sesión
$nombreAdmin = $_SESSION['admin_nombre'] ?? 'Admin';

// Obtener ID del TikTok a editar
$id = $_GET['id'] ?? null;

// Obtener los datos actuales del TikTok
$tiktok = $controller->ver($id);

if (!$tiktok) {
    die('TikTok no encontrado');
}

// Procesar formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    
    // Forzar que publicado_por sea el admin en sesión
    $publicado_por = $nombreAdmin;

    // Actualizar TikTok sin modificar video_id
    $controller->editar($id, $url, $descripcion, $publicado_por);

    // Redirigir a la lista de TikToks
    header('Location: index.php?page=tiktok');
    exit;
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h2 class="fw-bold text-white mb-4 text-center">✏️ Editar TikTok</h2>

        <div class="card text-white mx-auto" style="max-width: 600px; padding: 30px;">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-white">URL del TikTok</label>
                    <input type="url" name="url" class="form-control" 
                           value="<?= htmlspecialchars($tiktok['url'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Descripción</label>
                    <textarea name="descripcion" class="form-control"><?= htmlspecialchars($tiktok['descripcion'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Publicado Por</label>
                    <input type="text" name="publicado_por" class="form-control" 
                           value="<?= htmlspecialchars($nombreAdmin) ?>" readonly>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=tiktok" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
<main>