<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error = null;

// Para desarrollo: limpiar la sesión para evitar redirecciones infinitas
if (isset($_SESSION['admin'])) {
    unset($_SESSION['admin']);
}

// Si ya está logueado, redirige al panel
if (isset($_SESSION['admin'])) {
    header("Location: index.php?page=panel");
    exit;
}

// Si llega un error desde AuthController, se mostrará
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
?>

<!-- Login administradores -->
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 fw-bold text-white">Iniciar Sesión</h3>

                    <?php if ($error) : ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?page=auth/login" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label text-white">Usuario</label>
                            <input type="text" style="border: 2px solid #00ff00;" name="usuario" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Contraseña</label>
                            <div class="input-group">
                                <input type="password" style="border: 2px solid #00ff00;" name="password" id="password" class="form-control" required>
                                <button type="button" class="btn btn-neon" id="togglePassword" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-neon px-5 py-2">
                                Iniciar Sesión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para ver/ocultar contraseña -->
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.innerHTML = type === 'password' 
            ? '<i class="bi bi-eye"></i>' 
            : '<i class="bi bi-eye-slash"></i>';
    });
</script>
