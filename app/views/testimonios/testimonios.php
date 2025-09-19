<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/TestimoniosController.php';

if (!isset($db)) {
    require_once __DIR__ . '/../../config/database.php';
}

$controller = new TestimoniosController();
$registros = $controller->listarTestimonios();
?>
<body>
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5" style="margin-top:100px;">
            <a href="index.php?page=panel" class="btn btn-neon rounded-circle" style="width:60px;height:60px;">
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">💬 Gestión Testimonios</h1>
        </div>

        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Mensaje</th>
                        <th>Imagen</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $row): ?>
                        <tr>
                            <td><?= $row['id_testimonio'] ?></td>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['mensaje'])) ?></td>
                            <td>
                                <?php if (!empty($row['img'])): ?>
                                    <img src="app/uploads/testimonios/<?= htmlspecialchars($row['img']) ?>" alt="Imagen"
                                     class="img-thumbnail border-success"
                                        style="max-width:90px; max-height:90px; border: 2px solid #0f0;border-radius: 5px;">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td><?= $row['fecha_registro'] ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="index.php?page=editar_testimonios&id=<?= $row['id_testimonio'] ?>" 
                                    class="btn btn-warning btn-sm" style="width: 80px;">
                                    Editar
                                    </a>
                                    <a href="index.php?page=eliminar_testimonios&id=<?= $row['id_testimonio'] ?>" 
                                    class="btn btn-danger btn-sm" 
                                    style="width: 80px;" 
                                    onclick="return confirm('¿Seguro que deseas eliminar este testimonio?')">
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