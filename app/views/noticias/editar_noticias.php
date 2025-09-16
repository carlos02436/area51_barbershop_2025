<?php
require_once __DIR__ . '/../../controllers/NoticiasController.php';

$controller = new NoticiasController($db);
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php?page=noticias");
    exit();
}

$noticia = $controller->ver($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->editar(
        $id,
        $_POST['titulo'],
        $_POST['contenido'],
        $_POST['publicado_por']
    );
    header("Location: index.php?page=noticias");
    exit();
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Noticia</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <form action="index.php?page=editar_noticias&id=<?= $noticia['id_noticia'] ?>" method="POST" class="text-white">

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control"
                        value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido</label>
                    <textarea name="contenido" id="contenido" class="form-control" rows="5" required><?= htmlspecialchars($noticia['contenido']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="publicado_por" class="form-label">Publicado por</label>
                    <input type="text" name="publicado_por" id="publicado_por" class="form-control"
                        value="<?= htmlspecialchars($noticia['publicado_por'] ?? '') ?>">
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=noticias" class="btn btn-danger" style="width:80px;">Cancelar</a>
                    <button type="submit" class="btn btn-neon" style="width:80px;">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
<main>