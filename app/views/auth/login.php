<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers de seguridad mejorados
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

// Si ya está logueado, redirige al panel principal
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /area51_barbershop_2025/index.php?page=panel');
    exit();
}

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Administrador.php';

$adminModel = new Administrador($db);
$error = $adminModel->procesarLogin();
?>

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 fw-bold text-white">Bienvenid@ Administrador(@)</h3>
                    <p class="text-center mb-4 text-white fs-5">Por favor, inicia sesión</p>

                    <form method="POST" action="" autocomplete="off">
                        <?php if ($error): ?>
                            <div class="alert alert-danger mt-3 text-center"><?= htmlspecialchars($error); ?></div>
                        <?php endif; ?>
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
                            <button type="submit" class="btn btn-neon px-5 py-2">Iniciar Sesión</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.innerHTML = type === 'password' ?
            '<i class="bi bi-eye"></i>' :
            '<i class="bi bi-eye-slash"></i>';
    });

    // Prevenir acceso por back/forward del navegador 
    window.onload = function() {
        if (window.performance && window.performance.navigation.type === 2) {
            window.location.replace('/area51_barbershop_2025/index.php?page=login');
        }
    };

    // Prevenir caché de la página
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    // Limpiar historial al cargar
    if (window.history && window.history.pushState) {
        window.history.pushState(null, null, window.location.href);
        window.addEventListener('popstate', function() {
            window.history.pushState(null, null, window.location.href);
        });
    }
</script>