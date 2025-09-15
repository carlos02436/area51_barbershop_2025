<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/controllers/VideoController.php';
require_once __DIR__ . '/../../../config/database.php';

$controller = new VideoController($db);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $link = $_POST['link'] ?? '';
    $publicado_por = $_SESSION['admin_logged_in'] ?? 'admin'; // Usuario logueado

    // Limitar a máximo 3 videos activos
    $videosActivos = $controller->index();
    if (count($videosActivos) >= 3) {
        $error = "Ya hay 3 videos activos. Elimina uno para poder agregar otro.";
    } else {
        $controller->crear($titulo, $link, $publicado_por);
        $success = "Video publicado correctamente";
    }
}
?>
<body>
    <div class="container py-5">
        <h2 class="text-white mb-4">➕ Publicar Video</h2>
        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-white">Título</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Link del Video</label>
                <input type="url" name="link" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Publicar</button>
        </form>
    </div>
<main>