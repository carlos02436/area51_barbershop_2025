<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../../controllers/VideoController.php';
require_once __DIR__ . '/../../../config/database.php';

$controller = new VideoController($db);
$videos = $controller->index();
?>
<body>
    <div class="container py-5">
        <h1 class="text-white mb-4">🎬 Videos YouTube</h1>

        <a href="crear_youtube.php" class="btn btn-success mb-3">➕ Publicar Nuevo Video</a>

        <table class="table table-dark table-hover text-white">
            <thead>
                <tr>
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
                    <td><a href="<?= $video['link'] ?>" target="_blank">Ver Video</a></td>
                    <td><?= htmlspecialchars($video['publicado_por']) ?></td>
                    <td><?= $video['fecha_publicacion'] ?></td>
                    <td>
                        <a href="editar_youtube.php?id=<?= $video['id_video'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="eliminar_youtube.php?id=<?= $video['id_video'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Estás seguro que deseas eliminar este video?');">
                            Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<main>