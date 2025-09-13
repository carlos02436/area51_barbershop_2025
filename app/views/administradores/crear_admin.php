<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Admin</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">

        <!-- Mensajes -->
        <?php if (!empty($_SESSION['error_admin'])): ?>
            <div class="alert alert-warning"><?= $_SESSION['error_admin']; unset($_SESSION['error_admin']); ?></div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success_admin'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_admin']; unset($_SESSION['success_admin']); ?></div>
        <?php endif; ?>

        <form method="post" action="index.php?page=guardar_admin" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label text-white">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Usuario</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Foto</label>
                <input type="file" name="img_admin" class="form-control" accept="image/*">
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between w-100 mx-auto">
                <a href="index.php?page=administradores" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-neon">Crear Admin</button>
            </div>
        </form>
    </div>
</div>
<main>