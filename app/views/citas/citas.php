<?php
require_once __DIR__ . '/../../controllers/CitasController.php';
if (!isset($db)) require_once __DIR__ . '/../../config/database.php';

$citasController = new CitasController($db);
$citas = $citasController->listarCitas(); // JOIN con clientes, barberos y servicios
?>

<body>
    <div class="container py-5" style="margin-top:100px;">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start">
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;">
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">📰 Gestión de Citas</h1>
        </div>

        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr style="position: sticky; top: 0; z-index: 1;">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Barbero</th>
                        <th>Servicio</th>
                        <th>Imagen</th> <!-- Nueva columna -->
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
                                <td><?= htmlspecialchars($c['nombre_cliente']) ?></td>
                                <td><?= htmlspecialchars($c['apellido_cliente']) ?></td>
                                <td><?= htmlspecialchars($c['barbero']) ?></td>
                                <td><?= htmlspecialchars($c['servicio']) ?></td>
                                <td>
                                    <?php if(!empty($c['servicio_imagen'])): ?>
                                        <img src="app/uploads/servicios/<?= htmlspecialchars($c['servicio_imagen']) ?>" 
                                            alt="<?= htmlspecialchars($c['servicio']) ?>" 
                                            style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                                    <?php else: ?>
                                        <span class="text-muted">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($c['fecha_cita']))) ?></td>
                                <td><?= htmlspecialchars(date('H:i', strtotime($c['hora_cita']))) ?></td>
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
                            <td colspan="10" class="text-center">No hay citas registradas</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<main>