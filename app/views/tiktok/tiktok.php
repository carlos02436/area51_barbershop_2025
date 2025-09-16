<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) session_start();

// Incluir controlador y base de datos
require_once __DIR__ . '/../../controllers/TiktokController.php';
require_once __DIR__ . '/../../../config/database.php';

// Instanciar controlador y obtener videos TikTok activos
$controller = new TiktokController($db);
$tiktoks = $controller->listarVideos($limit = 3); // Ajusta el límite según necesidad
?>
<body>
    <div class="container py-5">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> 
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">🎵 Videos TikTok</h1>
        </div>

        <!-- Botón Crear Nuevo TikTok -->
        <div class="d-flex justify-content-end mb-3">
            <a href="index.php?page=crear_tiktok" class="btn btn-neon">➕ Publicar Nuevo TikTok</a>
        </div>

        <!-- Contenedor con scroll vertical -->
        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr style="position: sticky; top: 0; z-index: 1;">
                        <th>ID</th>
                        <th>URL</th>
                        <th>Descripción</th>
                        <th>Publicado Por</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tiktoks as $tiktok): ?>
                    <tr>
                        <td><?= $tiktok['id_tiktok'] ?? '' ?></td>
                        <td>
                            <?php if(!empty($tiktok['url'])): ?>
                                <a href="<?= htmlspecialchars($tiktok['url']) ?>" target="_blank"><?= htmlspecialchars($tiktok['url']) ?></a>
                            <?php else: ?>
                                <span class="text-muted">No disponible</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($tiktok['descripcion'] ?? '') ?></td>
                        <td><?= htmlspecialchars($tiktok['publicado_por'] ?? 'Desconocido') ?></td>
                        <td><?= $tiktok['fecha_registro'] ?? '' ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="index.php?page=editar_tiktok&id=<?= $tiktok['id_tiktok'] ?? '' ?>" 
                                   class="btn btn-warning btn-sm" style="width: 80px;">Editar</a>
                                <a href="index.php?page=eliminar_tiktok&id=<?= $tiktok['id_tiktok'] ?? '' ?>" 
                                   class="btn btn-danger btn-sm" style="width: 80px;"
                                   onclick="return confirm('¿Estás seguro que deseas eliminar este TikTok?');">
                                    Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<main>