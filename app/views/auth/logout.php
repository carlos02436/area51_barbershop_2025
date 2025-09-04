<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Registrar el logout (opcional - para logging)
if (isset($_SESSION['admin_logged_in'])) {
    error_log("Admin logout: " . ($_SESSION['admin_id'] ?? 'unknown') . " at " . date('Y-m-d H:i:s'));
}

// Limpiar todas las variables de sesión
$_SESSION = [];

// Destruir la sesión y eliminar la cookie - MEJORADO
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Regenerar ID de sesión para mayor seguridad
session_regenerate_id(true);
session_destroy();

// Headers anti-caché mejorados
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("X-Frame-Options: DENY");

// Redirigir al login con mensaje de confirmación - RUTA CORREGIDA
header('Location: /area51_barbershop_2025/index.php?page=login&msg=logout_success');
exit();
?>