<?php
// Obtener datos del administrador por id
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php?page=administradores');
    exit;
}

$stmt = $db->prepare("SELECT * FROM administradores WHERE id_admin = :id");
$stmt->execute([':id' => $id]);
$admin = $stmt->fetch();

if (!$admin) {
    header('Location: index.php?page=administradores');
    exit;
}
?>

<body>
<div class="container mt-5">
    <h2 class="text-white mb-4">Editar Administrador</h2>

    <!-- Mensajes -->
    <?php if (!empty($_SESSION['error_admin'])): ?>
        <div class="alert alert-warning"><?= $_SESSION['error_admin']; unset($_SESSION['error_admin']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success_admin'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success_admin']; unset($_SESSION['success_admin']); ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?page=actualizar_admin&id=<?= $admin['id_admin'] ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label text-white">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($admin['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label text-white">Usuario</label>
            <input type="text" name="usuario" class="form-control" value="<?= htmlspecialchars($admin['usuario']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label text-white">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($admin['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label text-white">Contraseña</label>
            <input type="password" name="password" class="form-control" value="<?= htmlspecialchars($admin['password']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label text-white">Foto</label>
            <?php if (!empty($admin['img_admin'])): ?>
                <div class="mb-2">
                    <img src="app/uploads/admin/<?= htmlspecialchars($admin['img_admin']) ?>"
                         alt="Imagen del administrador"
                         class="img-thumbnail" style="max-width: 120px;">
                </div>
            <?php endif; ?>
            <input type="file" name="img_admin" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Administrador</button>
        <a href="index.php?page=administradores" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<main>