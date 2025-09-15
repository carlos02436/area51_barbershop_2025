<?php
// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir la base de datos (ruta corregida)
require __DIR__ . '/../../../config/database.php';

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
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Administrador</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">

            <!-- Mensajes -->
            <?php if (!empty($_SESSION['error_admin'])): ?>
                <div class="alert alert-warning py-4"><?= $_SESSION['error_admin']; unset($_SESSION['error_admin']); ?></div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success_admin'])): ?>
                <div class="alert alert-success py-4"><?= $_SESSION['success_admin']; unset($_SESSION['success_admin']); ?></div>
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

                <!-- Contraseña con ícono -->
                <div class="mb-3">
                    <label class="form-label text-white">Contraseña</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" value="<?= htmlspecialchars($admin['password']) ?>" required>
                        <span class="input-group-text btn-neon" id="togglePassword" style="cursor: pointer;">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Foto</label>
                    <?php if (!empty($admin['img_admin'])): ?>
                        <div class="mb-2">
                            <img src="app/uploads/admin/<?= htmlspecialchars($admin['img_admin']) ?>" alt="Imagen actual" class="img-thumbnail" style="max-width: 120px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="img_admin" class="form-control" accept="image/*">
                    <input type="hidden" name="img_actual" value="<?= htmlspecialchars($admin['img_admin']) ?>">
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between w-100 mx-auto">
                    <a href="index.php?page=administradores" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Actualizar Admin</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePasswordIcon.classList.toggle('bi-eye');
            togglePasswordIcon.classList.toggle('bi-eye-slash');
        });
    </script>
<main>