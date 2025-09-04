<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers de seguridad y anti-cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

// Si ya está logueado, redirige al panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php?page=panel');
    exit();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Administrador.php';

$adminModel = new Administrador($db);
$error = '';

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Intentar login
    $admin = $adminModel->login($usuario, $password); // Debe devolver datos del admin o false

    if ($admin) {
        // Guardar datos en sesión
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id']; // ejemplo
        $_SESSION['admin_nombre'] = $admin['nombre'];

        // Redirigir al panel
        header('Location: index.php?page=panel');
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!-- FORMULARIO LOGIN -->
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100 justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
      <div class="card shadow-lg border-0">
        <div class="card-body p-4">
          <h3 class="text-center mb-4 fw-bold text-white">
            Bienvenid@ Administrador(@)
          </h3>
          <p class="text-center mb-4 text-white fs-5">
            Por favor, inicia sesión
          </p>

          <form method="POST" action="" autocomplete="off">
          <!-- Mensaje de error -->
          <?php if ($error): ?>
            <div class="alert alert-danger mt-3 text-center">
              <?= $error; ?>
            </div>
          <?php endif; ?>
            <!-- Usuario -->
            <div class="mb-3">
              <label class="form-label text-white">Usuario</label>
              <input 
                type="text" 
                name="usuario" 
                class="form-control" 
                style="border: 2px solid #00ff00;" 
                required 
                autofocus
              >
            </div>

            <!-- Contraseña -->
            <div class="mb-3">
              <label class="form-label text-white">Contraseña</label>
              <div class="input-group">
                <input 
                  type="password" 
                  name="password" 
                  id="password" 
                  class="form-control" 
                  style="border: 2px solid #00ff00;" 
                  required
                >
                <button 
                  type="button" 
                  class="btn btn-neon" 
                  id="togglePassword" 
                  tabindex="-1"
                >
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <!-- Botón de envío -->
            <div class="d-grid mt-4">
              <button 
                type="submit" 
                class="btn btn-neon px-5 py-2"
              >
                Iniciar Sesión
              </button>
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

    // Prevenir acceso por back/forward del navegador - CON RUTA CORREGIDA
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