<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';
if (!isset($db)) require_once __DIR__ . '/../../config/database.php';

$clienteController = new ClienteController();
$clientes = $clienteController->listarClientes();
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start">
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;">
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">👥 Gestión de Clientes</h1>
        </div>

        <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr style="position: sticky; top: 0; z-index: 1;">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($clientes) && count($clientes) > 0): ?>
                        <?php foreach($clientes as $c): ?>
                            <tr>
                                <td><?= $c['id_cliente'] ?></td>
                                <td><?= htmlspecialchars($c['nombre']) ?></td>
                                <td><?= htmlspecialchars($c['apellido']) ?></td>
                                <td><?= htmlspecialchars($c['telefono']) ?></td>
                                <td><?= htmlspecialchars($c['correo']) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="index.php?page=editar_cliente&id=<?= $c['id_cliente'] ?>" 
                                           class="btn btn-warning btn-sm" style="width:80px;">Editar</a>
                                        <a href="index.php?page=eliminar_cliente&id=<?= $c['id_cliente'] ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('¿Eliminar este cliente?')" 
                                           style="width:80px;">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay clientes registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<main>