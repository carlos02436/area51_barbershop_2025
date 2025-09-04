<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php?page=panel");
    exit;
}
$error = $error ?? null;
?>
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

          <form method="POST" action="index.php?page=login" autocomplete="off">
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger mt-3 text-center">
                <?= htmlspecialchars($error); ?>
              </div>
            <?php endif; ?>

            <!-- Campo Usuario -->
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

            <!-- Campo Contraseña -->
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

            <!-- Botón Iniciar Sesión -->
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

<!-- Script -->
<script>
  (function(){
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    if (togglePassword && password) {
      togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.innerHTML = type === 'password' 
          ? '<i class="bi bi-eye"></i>' 
          : '<i class="bi bi-eye-slash"></i>';
      });
    }

    // Prevenir que el botón atrás/adelante muestre panel tras logout
    if (window.history && window.history.pushState) {
      window.history.pushState(null, null, window.location.href);
      window.addEventListener('popstate', function() {
        window.history.pushState(null, null, window.location.href);
      });
    }

    // Evitar caché de página en algunos navegadores
    window.addEventListener('pageshow', function(event) {
      if (event.persisted) {
        window.location.reload();
      }
    });
  })();
</script>