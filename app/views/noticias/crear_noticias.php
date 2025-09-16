<?php
require_once __DIR__ . '/../../controllers/NoticiasController.php';
if (!isset($db)) {
    require_once __DIR__ . '/../../config/database.php';
}

$controller = new NoticiasController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');
    $publicado_por = trim($_POST['publicado_por'] ?? null);

    if ($titulo !== '' && $contenido !== '') {
        $controller->crear($titulo, $contenido, $publicado_por);
        header("Location: index.php?page=noticias");
        exit();
    }
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">📰 Crear Noticia</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <form action="index.php?page=crear_noticias" method="POST" class="text-white">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido</label>
                    <textarea name="contenido" id="contenido" class="form-control" rows="5" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="publicado_por" class="form-label">Publicado por</label>
                    <input type="text" name="publicado_por" id="publicado_por" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=noticias" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Guardar Noticia</button>
                </div>
            </form>
        </div>
    </div>
<main>