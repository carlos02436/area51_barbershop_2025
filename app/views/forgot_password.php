<?php
// No volvemos a requerir database.php porque ya está en index.php

// Requerir PHPMailer (asegúrate de que la carpeta src/ esté en la raíz del proyecto)
require_once __DIR__ . '/../../src/PHPMailer.php';
require_once __DIR__ . '/../../src/SMTP.php';
require_once __DIR__ . '/../../src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    $stmt = $db->prepare("SELECT * FROM administradores WHERE email = :email LIMIT 1");
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $password = $admin['password']; // ⚠️ En producción deberías usar un token temporal
        $asunto = "Recuperación de contraseña - Área 51 BarberShop";
        $mensaje = "Hola " . $admin['usuario'] . ",\n\n";
        $mensaje .= "Tu contraseña actual es: $password\n\n";
        $mensaje .= "Si deseas cambiarla, entra aquí:\n";
        $mensaje .= "http://localhost/area51_barbershop_2025/index.php?page=reset_password&email=" . urlencode($email);

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'area51barberia2025@gmail.com';  // 🔹 pon tu correo Gmail
            $mail->Password   = 'poun ajyb xqhh wraz'; // 🔹 tu clave o app password de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet = 'UTF-8';       // Fuerza UTF-8
            $mail->Encoding = 'base64';     // Evita que se rompan tildes y eñes


            $mail->setFrom('area51barberia2025@gmail.com', 'Area51 BarberShop');
            $mail->addAddress($email);

            $mail->isHTML(false);
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;

            if ($mail->send()) {
                $msg = "✅ Se envió un correo con tu contraseña.";
            } else {
                $msg = "❌ Error al enviar el correo.";
            }
        } catch (Exception $e) {
            $msg = "❌ No se pudo enviar el correo. Error: " . $mail->ErrorInfo;
        }
    } else {
        $msg = "⚠️ Correo no registrado.";
    }
}
?>
<body>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4 fw-bold text-white">Recuperar Contraseña</h3>

                        <?php if (!empty($msg)): ?>
                            <div class="alert alert-info text-center">
                                <?= htmlspecialchars($msg); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?page=forgot_password">
                            <div class="mb-3">
                                <label class="form-label text-white">Correo</label>
                                <input type="email" name="email" class="form-control" required placeholder="correo@dominio.com">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-neon px-5 py-2">Enviar</button>
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
<main>