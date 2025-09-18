<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/NoticiasController.php';
if (!isset($db)) require_once __DIR__ . '/../../config/database.php';

$controller = new NoticiasController($db);
$id = $_GET['id'] ?? null;
if (!$id) header('Location: index.php?page=noticias');

$noticia = $controller->obtenerNoticia($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $controller->actualizarNoticia($id, $titulo, $contenido);
    header('Location: index.php?page=noticias');
    exit();
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Noticia</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <form action="" method="POST" class="text-white">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control"
                        value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido</label>
                    <textarea name="contenido" id="contenido" class="form-control" rows="5" required><?= htmlspecialchars($noticia['contenido']) ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=noticias" class="btn btn-danger" style="width:100px;">Cancelar</a>
                    <button type="submit" class="btn btn-neon" style="width:100px;">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
<main>