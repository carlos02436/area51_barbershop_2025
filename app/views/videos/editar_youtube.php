<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/controllers/VideoController.php';
require_once __DIR__ . '/../../../config/database.php';

$controller = new VideoController($db);
$id = $_GET['id'] ?? null;
$video = $controller->ver($id);
$error = '';
$success = '';

if (!$video) {
    die('Video no encontrado');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $link = $_POST['link'] ?? '';
    $controller->editar($id, $titulo, $link);
    $success = "Video actualizado correctamente";
    $video = $controller->ver($id);
}
?>
<body>
    <div class="container py-5">
        <h2 class="text-white mb-4">✏️ Editar Video</h2>
        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-white">Título</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($video['titulo']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Link del Video</label>
                <input type="url" name="link" class="form-control" value="<?= htmlspecialchars($video['link']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
<main>