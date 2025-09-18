<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/BarberoController.php';

$controller = new BarberoController();
$barberos = $controller->listar();
?>

<body>
    <div class="container py-5">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">💈 Gestión de Barberos</h1>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a href="index.php?page=crear_barbero" class="btn btn-neon">➕ Nuevo Barbero</a>
        </div>
        <!-- Contenedor con scroll vertical -->
        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr style="position: sticky; top: 0; z-index: 1;">
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Fecha Contratación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($barberos as $barbero): ?>
                        <tr>
                            <td><?= $barbero['id_barbero'] ?></td>

                            <!-- Mostrar miniatura de la imagen -->
                            <td>
                                <?php if (!empty($barbero['img_barberos'])): ?>
                                    <img src="app/uploads/barberos/<?= htmlspecialchars($barbero['img_barberos']) ?>"
                                        alt="Imagen del barbero"
                                        class="img-thumbnail border-success"
                                        style="max-width: 100px; max-height: 80px; border: 2px solid #28a745;">
                                <?php else: ?>
                                    <p class="text-warning small mb-0">⚠️ Sin imagen</p>
                                <?php endif; ?>
                            </td>

                            <td><?= $barbero['nombre'] ?></td>
                            <td><?= $barbero['especialidad'] ?></td>
                            <td><?= $barbero['telefono'] ?></td>
                            <td><?= $barbero['email'] ?></td>
                            <td><?= $barbero['fecha_contratacion'] ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="index.php?page=editar_barbero&id=<?= $barbero['id_barbero'] ?>" 
                                    class="btn btn-warning btn-sm" style="width: 80px;">Editar</a>
                                    <a href="index.php?page=eliminar_barbero&id=<?= $barbero['id_barbero'] ?>" 
                                    class="btn btn-danger btn-sm" style="width: 80px;"
                                    onclick="return confirm('¿Seguro que quieres eliminar este barbero?')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <main>