<?php
// Archivo: app/views/auth/check_session.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers para prevenir caché
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");

// Verificar timeout de sesión
$timeout = 1800; // 30 minutos
$session_valid = false;

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] <= $timeout) {
            $_SESSION['last_activity'] = time(); // Actualizar última actividad
            $session_valid = true;
        } else {
            // Sesión expirada
            session_unset();
            session_destroy();
        }
    } else {
        $_SESSION['last_activity'] = time();
        $session_valid = true;
    }
}

$response = [
    'logged_in' => $session_valid,
    'timestamp' => time()
];

echo json_encode($response);
exit();
?>