<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya está logueado, ir directo al panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php?page=panel");
    exit();
}

// Procesar login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once __DIR__ . "/../../config/database.php";

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Validar en BD (⚠️ solo para prueba; mejor usar password_hash)
    $stmt = $db->prepare("SELECT * FROM administradores WHERE usuario = :usuario AND password = :password");
    $stmt->bindParam(":usuario", $usuario);
    $stmt->bindParam(":password", $password);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id_admin'];
        $_SESSION['admin_usuario'] = $admin['usuario'];
        $_SESSION['admin_nombre'] = $admin['nombre'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_img'] = $admin['img_admin'];

        if (isset($_POST['rememberMe'])) {
            setcookie("usuario", $usuario, time() + (7 * 24 * 60 * 60), "/");
            setcookie("password", $password, time() + (7 * 24 * 60 * 60), "/");
        } else {
            setcookie("usuario", "", time() - 3600, "/");
            setcookie("password", "", time() - 3600, "/");
        }

        header("Location: index.php?page=panel");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}

// Precargar desde cookies si existen
$usuario_cookie = $_COOKIE['usuario'] ?? '';
$password_cookie = $_COOKIE['password'] ?? '';
$error = $error ?? '';
?>
<body>
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
                  <input
                    type="text"
                    name="usuario"
                    class="form-control"
                    style="border: 2px solid #00ff00;"
                    value="<?= htmlspecialchars($usuario_cookie) ?>"
                    required
                    autofocus
                    placeholder="Ingresar Usuario">
                </div>

                <!-- Campo Contraseña -->
                <div class="mb-3">
                  <div class="input-group">
                    <input
                      type="password"
                      name="password"
                      id="password"
                      class="form-control"
                      style="border: 2px solid #00ff00;"
                      value="<?= htmlspecialchars($password_cookie) ?>"
                      required
                      placeholder="Ingresar Contraseña">
                    <button
                      type="button"
                      class="btn btn-neon"
                      id="togglePassword"
                      tabindex="-1">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>

                <!-- Recuérdame + Olvidaste tu contraseña -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="form-check mb-0">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      id="rememberMe"
                      name="rememberMe"
                      value="1"
                      <?= !empty($usuario_cookie) ? 'checked' : '' ?>>
                    <label class="form-check-label text-white mb-0" for="rememberMe">
                      Recuérdame
                    </label>
                  </div>

                  <div>
                    <a href="index.php?page=forgot_password" class="text-white text-decoration-none">
                      ¿Olvidaste tu contraseña?
                    </a>
                  </div>
                </div>

                <!-- Botón Iniciar Sesión -->
                <div class="d-grid mt-2">
                  <button type="submit" class="btn btn-neon px-5 py-2">
                    Iniciar Sesión
                  </button>
                </div>

                <!-- Botón Cancelar -->
                <div class="d-grid mt-2">
                  <button type="button" class="btn btn-outline-light px-5 py-2" onclick="window.location.href='index.php?page=home'">
                    Cancelar
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
      (function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        if (togglePassword && password) {
          togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.innerHTML = type === 'password' ?
              '<i class="bi bi-eye"></i>' :
              '<i class="bi bi-eye-slash"></i>';
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
<main>