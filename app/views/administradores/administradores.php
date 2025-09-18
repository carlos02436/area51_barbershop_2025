<?php
require_once __DIR__ . '/../auth_admin.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php?page=login");
    exit;
}

// Obtener administradores de la base de datos
$stmt = $db->query("SELECT * FROM administradores");
$admins = $stmt->fetchAll();
?>

<body>
    <div class="container py-5">
            <!-- Título y botón HOME -->
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
                <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> <i class="bi bi-house-fill fs-3"></i>
                </a>
                <h1 class="fw-bold display-5 text-white mb-0">💈 Gestión de Administradores</h1>
            </div>
            <div class="d-flex justify-content-end mb-3">
                <a href="index.php?page=crear_admin" class="btn btn-neon">➕ Nuevo Admin</a>
            </div>    
        
            <div class="table-wrapper rounded shadow-sm" style="max-height:500px; overflow-y:auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                <tr style="position: sticky; top: 0; z-index: 1;">
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($admins as $admin): ?>
                    <tr>
                        <td><?= $admin['id_admin'] ?></td>
                        <td>
                            <?php if (!empty($admin['img_admin'])): ?>
                                <img src="app/uploads/admin/<?= htmlspecialchars($admin['img_admin']) ?>"
                                    alt="Imagen del administrador"
                                    class="img-thumbnail border-success"
                                    style="max-width:90px; max-height:190px; border: 2px solid #0f0;border-radius: 5px;"">
                            <?php else: ?>
                                <p class="text-warning small mb-0">⚠️ Sin imagen</p>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($admin['nombre']) ?></td>
                        <td><?= htmlspecialchars($admin['usuario']) ?></td>
                        <td><?= htmlspecialchars($admin['email']) ?></td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="index.php?page=editar_admin&id=<?= $admin['id_admin'] ?>" class="btn btn-sm btn-warning" style="width: 80px;">Editar</a>
                                <a href="index.php?page=eliminar_admin&id=<?= $admin['id_admin'] ?>" class="btn btn-sm btn-danger" style="width: 80px;">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Mensajes -->
            <?php if (!empty($_SESSION['error_admin'])): ?>
                <div class="alert alert-warning"><?= $_SESSION['error_admin']; unset($_SESSION['error_admin']); ?></div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success_admin'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success_admin']; unset($_SESSION['success_admin']); ?></div>
            <?php endif; ?>
        </div>
    </div>
<main>