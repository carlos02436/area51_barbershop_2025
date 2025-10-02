<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/PQRSController.php';

$pqrsController = new PQRSController($db);
$registros = $pqrsController->listar();
?>
<body>
    <div class="container py-5">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=panel" 
               class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" 
               style="width: 60px; height: 60px;"> 
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">📊 Gestión PQRS</h1>
        </div>

        <!-- Contenedor con scroll vertical -->
        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr style="position: sticky; top: 0; z-index: 1;">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Mensaje</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($registros)): ?>
                        <tr>
                            <td colspan="7" class="text-center fw-bold text-dark">
                                No existen registros para mostrar.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($registros as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_pqrs']) ?></td>
                                <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellidos']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['tipo']) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['mensaje'])) ?></td>
                                <td><?= htmlspecialchars($row['estado']) ?></td>
                                <td class="text-center">
                                    <a href="index.php?page=editar_pqrs&id=<?= $row['id_pqrs'] ?>" 
                                       class="btn btn-warning btn-sm" style="width:80px;">Editar</a>
                                    <a href="index.php?page=eliminar_pqrs&id=<?= $row['id_pqrs'] ?>" 
                                       class="btn btn-danger btn-sm" style="width:80px;" 
                                       onclick="return confirm('¿Marcar como resuelto?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<main>