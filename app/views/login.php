<?php
$error = null;

// Procesar login si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Buscar usuario en la BD
    $stmt = $db->prepare("SELECT * FROM administradores WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar usuario y contraseña
    // Usando texto plano; para producción, usa password_hash y password_verify
    if ($admin && $password === $admin['password']) {
        $_SESSION['admin'] = [
            'id_admin' => $admin['id_admin'],
            'nombre'   => $admin['nombre'],
            'usuario'  => $admin['usuario'],
            'email'    => $admin['email']
        ];
        header("Location: index.php?page=panel");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!-- FORMULARIO LOGIN -->
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 fw-bold text-white">Bienvenid@ Administrador(@)</h3>

                    <?php if ($error) : ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?page=panel" autocomplete="off">
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

togglePassword.addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.innerHTML = type === 'password' 
        ? '<i class="bi bi-eye"></i>' 
        : '<i class="bi bi-eye-slash"></i>';
});
</script>