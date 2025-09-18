<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../config/database.php'; // conexión BD

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $nuevaPassword = $_POST['nueva_password'];
    $confirmarPassword = $_POST['confirmar_password'];

    if ($nuevaPassword === $confirmarPassword) {
        // Cambiado $conn por $db
        $stmt = $db->prepare("UPDATE administradores SET password = :password WHERE email = :email");
        $stmt->bindParam(":password", $nuevaPassword, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $msg = "✅ Tu contraseña ha sido actualizada.";
        } else {
            $msg = "❌ Error al actualizar la contraseña.";
        }
    } else {
        $msg = "⚠️ Las contraseñas no coinciden.";
    }
}
?>
<body>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                <h3 class="text-center mb-4 fw-bold text-white">Restablecer Contraseña</h3>

                <?php if (!empty($msg)): ?>
                    <div class="alert alert-info text-center">
                    <?= htmlspecialchars($msg); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">

                    <!-- Nueva contraseña -->
                    <div class="mb-3 position-relative">
                        <label class="form-label text-white">Nueva contraseña</label>
                        <div class="input-group">
                            <input type="password" id="nueva_password" name="nueva_password" class="form-control" required>
                            <button class="btn btn-neon" type="button" onclick="togglePassword('nueva_password')">
                                👁
                            </button>
                        </div>
                    </div>

                    <!-- Confirmar contraseña -->
                    <div class="mb-3 position-relative">
                        <label class="form-label text-white">Confirmar contraseña</label>
                        <div class="input-group">
                            <input type="password" id="confirmar_password" name="confirmar_password" class="form-control" required>
                            <button class="btn btn-neon" type="button" onclick="togglePassword('confirmar_password')">
                                👁
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-neon px-5 py-2">Actualizar</button>
                    </div>
                    <div class="d-grid mt-2">
                        <a href="index.php?page=login" class="btn btn-outline-light px-5 py-2">Cancelar</a>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }
    </script>
<main>