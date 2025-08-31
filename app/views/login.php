<!-- Login administradores -->
<div class="container py-5 text-white" style="scroll-margin-top:80px;margin: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h3 class="text-center text-white mb-4">Iniciar Sesión</h3>
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="app/controllers/AuthController.php">
                        <div class="mb-3">
                            <label class="form-label text-white">Usuario</label>
                            <input type="text" name="usuario" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Contraseña</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <button type="button" class="btn btn-neon" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-neon d-block mx-auto px-5">
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
        // Alternar tipo de input
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Cambiar icono
        this.innerHTML = type === 'password' 
            ? '<i class="bi bi-eye"></i>' 
            : '<i class="bi bi-eye-slash"></i>';
    });
</script>