<?php
require_once __DIR__ . '/../../controllers/NoticiasController.php';
if (!isset($db)) require_once __DIR__ . '/../../config/database.php';

$noticiasController = new NoticiasController($db);
$registros = $noticiasController->listarNoticias();
?>
<body>
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> <i class="bi bi-house-fill fs-3"></i></a>
            <h1 class="fw-bold display-5 text-white mb-0">📰 Gestión de Noticias</h1>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a href="index.php?page=crear_noticias" class="btn btn-neon">➕ Nueva Noticia</a>
        </div>

        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr style="position: sticky; top: 0; z-index: 1;">
                        <th>ID</th>
                        <th>Título</th>
                        <th>Contenido</th>
                        <th>Fecha publicación</th>
                        <th>Publicado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $row): ?>
                        <tr>
                            <td><?= $row['id_noticia'] ?></td>
                            <td><?= htmlspecialchars($row['titulo']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['contenido'])) ?></td>
                            <td><?= $row['fecha_publicacion'] ?></td>
                            <td><?= $row['publicado_por'] ?? 'N/A' ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="index.php?page=editar_noticias&id=<?= $row['id_noticia'] ?>" 
                                    class="btn btn-warning btn-sm" style="width:80px;">Editar</a>
                                    <a href="index.php?page=eliminar_noticias&id=<?= $row['id_noticia'] ?>" 
                                    class="btn btn-danger btn-sm" 
                                    onclick="return confirm('¿Eliminar esta noticia?')" style="width:80px;">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<main>