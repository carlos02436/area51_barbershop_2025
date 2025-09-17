<?php
require_once __DIR__ . '/../../controllers/CitasController.php';
if (!isset($db)) require_once __DIR__ . '/../../config/database.php';

$citasController = new CitasController($db);
$citas = $citasController->listarCitas(); // Asegúrate que este método devuelva un array
?>

<body>
    <div class="container py-5" style="margin-top:100px;">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start">
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;">
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">💈 Gestión de Citas</h1>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="index.php?page=crear_cita" class="btn btn-neon">➕ Nueva Cita</a>
        </div>

        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr style="position: sticky; top: 0; z-index: 1;">
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Barbero</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($citas) && count($citas) > 0): ?>
                        <?php foreach($citas as $c): ?>
                            <tr>
                                <td><?= $c['id_cita'] ?></td>
                                <td><?= htmlspecialchars($c['id_cliente']) ?></td>
                                <td><?= htmlspecialchars($c['id_barbero']) ?></td>
                                <td><?= htmlspecialchars($c['id_servicio']) ?></td>
                                <td><?= htmlspecialchars($c['fecha_cita']) ?></td>
                                <td><?= htmlspecialchars($c['hora_cita']) ?></td>
                                <td><?= htmlspecialchars($c['estado']) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="index.php?page=editar_cita&id=<?= $c['id_cita'] ?>" class="btn btn-warning btn-sm" style="width:80px;">Editar</a>
                                        <a href="index.php?page=eliminar_cita&id=<?= $c['id_cita'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Cancelar esta cita?')" style="width:80px;">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay citas registradas</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<main>