<?php
require_once __DIR__ . '/../../controllers/ServicioController.php';

$controller = new ServicioController();
$servicios = $controller->listarServicios();
?>

<body>
    <div class="container py-5">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">📊 Gestión de Servicios</h1>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a href="index.php?page=crear_servicio" class="btn btn-neon">Agregar Servicio</a>
        </div>

        <!-- Contenedor con scroll vertical -->
        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr style="position: sticky; top: 0; z-index: 1;">
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Observación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicios as $row): ?>
                        <tr>
                            <td><?= $row['id_servicio'] ?></td>
                            
                            <!-- Mostrar miniatura de la imagen -->
                            <td>
                                <?php if (!empty($row['img_servicio'])): ?>
                                    <img src="app/uploads/servicios/<?= htmlspecialchars($row['img_servicio']) ?>" 
                                        alt="Imagen del servicio" 
                                        class="img-thumbnail border-success"
                                        style="max-width: 100px; max-height: 80px; border: 2px solid #28a745;">
                                <?php else: ?>
                                    <p class="text-warning small mb-0">⚠️ Sin imagen</p>
                                <?php endif; ?>
                            </td>
                            
                            <td><?= $row['nombre'] ?></td>
                            <td><?= $row['descripcion'] ?></td>
                            <td>$<?= $row['precio'] ?></td>
                            <td><?= $row['observacion'] ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="index.php?page=editar_servicio&id=<?= $row['id_servicio'] ?>" 
                                    class="btn btn-sm btn-warning" style="width: 80px;">Editar</a>
                                    <a href="index.php?page=eliminar_servicio&id=<?= $row['id_servicio'] ?>" 
                                    class="btn btn-sm btn-danger" style="width: 80px;"
                                    onclick="return confirm('¿Deseas eliminar este servicio?')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <main>