<?php
// $citas debe venir del controlador o desde index.php
// Ejemplo: $citas = $citasController->listar();
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
            <h1 class="fw-bold display-5 text-white mb-0">📊 Gestión de Citas</h1>
        </div>

        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Barbero</th>
                        <th>Servicio</th>
                        <th>Imagen</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($citas)): ?>
                        <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><?= htmlspecialchars($cita['id_cita']) ?></td>
                            <td><?= htmlspecialchars($cita['cliente']) ?></td>
                            <td><?= htmlspecialchars($cita['barbero']) ?></td>
                            <td><?= htmlspecialchars($cita['servicio']) ?></td>
                            <td>
                                <?php if (!empty($cita['img_servicio'])): ?>
                                    <img src="app/uploads/servicios/<?= htmlspecialchars($cita['img_servicio']) ?>"
                                        alt="Servicio"
                                        class="img-thumbnail"
                                        style="max-width:100px; max-height:80px; border:2px solid #28a745;">
                                <?php else: ?>
                                    <p class="text-warning small mb-0">⚠️ Sin imagen</p>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($cita['fecha_cita']) ?></td>
                            <td><?= date("g:i A", strtotime($cita['hora_cita'])) ?></td>
                            <td><?= htmlspecialchars($cita['estado']) ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="index.php?page=editar_cita&id=<?= $cita['id_cita'] ?>" 
                                       class="btn btn-warning btn-sm">Editar</a>
                                    <a href="index.php?page=eliminar_cita&id=<?= $cita['id_cita'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('¿Eliminar esta cita?')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay citas registradas</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<main>