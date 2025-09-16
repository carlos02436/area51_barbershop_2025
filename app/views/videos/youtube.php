<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) session_start();

// Incluir controlador y base de datos
require_once __DIR__ . '/../../controllers/VideoController.php';
require_once __DIR__ . '/../../../config/database.php';

// Instanciar controlador y obtener videos
$controller = new VideoController($db);
$videos = $controller->index($limit = 3);
?>
<body>
    <div class="container py-5">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">🎬 Videos YouTube</h1>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a href="index.php?page=crear_youtube" class="btn btn-neon">➕ Publicar Nuevo Video</a>
        </div>

        <!-- Contenedor con scroll vertical -->
        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr style="position: sticky; top: 0; z-index: 1;">
                    <th>ID</th>
                    <th>Título</th>
                    <th>Link</th>
                    <th>Publicado Por</th>
                    <th>Fecha Publicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($videos as $video): ?>
                <tr>
                    <td><?= $video['id_video'] ?></td>
                    <td><?= htmlspecialchars($video['titulo']) ?></td>
                    <td><a class="text-decoration-none" href="<?= htmlspecialchars($video['link']) ?>" target="_blank"><?= htmlspecialchars($video['link']) ?></a></td>
                    <td><?= htmlspecialchars($video['publicado_por']) ?></td>
                    <td><?= $video['fecha_publicacion'] ?></td>
                    <td class="text-center">
                        <a href="index.php?page=editar_youtube&id=<?= $video['id_video'] ?>" 
                           class="btn btn-warning btn-sm" style="width: 80px;">Editar</a>
                        <a href="index.php?page=eliminar_youtube&id=<?= $video['id_video'] ?>" class="btn btn-danger btn-sm" style="width: 80px;"
                            onclick="return confirm('¿Estás seguro que deseas eliminar este video?');">
                            Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<main>