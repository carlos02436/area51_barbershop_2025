<?php
// Asegurarnos de que $citas siempre sea un array
if (!isset($citas) || !is_array($citas)) {
    $citas = [];
}
?>
<body>
    <div class="container py-5"> 
        <!-- Título y botón HOME --> 
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;"> 
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> <i class="bi bi-house-fill fs-3"></i> 
            </a> <h1 class="fw-bold display-5 text-white mb-0">📊 Gestión de Citas</h1> 
        </div> 
        <!-- Contenedor con scroll vertical --> 
        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr style="position: sticky; top: 0; z-index: 1;">
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Barbero</th>
                            <th>Servicio</th>
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
                                    <td><?= htmlspecialchars($cita['nombre_cliente'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($cita['apellido_cliente'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($cita['nombre_barbero'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($cita['nombre_servicio'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($cita['fecha_cita'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($cita['hora_cita'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($cita['estado'] ?? '') ?></td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2"> 
                                            <a href="index.php?page=editar_cita&id=<?= $cita['id_cita'] ?>" class="btn btn-warning btn-sm" style="width: 80px;">Editar</a> 
                                            <a href="index.php?page=eliminar_cita&id=<?= $cita['id_cita'] ?>" class="btn btn-danger btn-sm" style="width: 80px;" onclick="return confirm('¿Seguro que quieres eliminar esta cita?')">Eliminar</a> 
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
    </div>  
<main>