<?php
// auth_admin.php
// Protege todas las vistas del panel del administrador y previene cache

// Mantiene la sesión iniciada las 24Hr o hasta que se decida cerrar
if (session_status() === PHP_SESSION_NONE) {
    // Configuración de duración de sesión
    ini_set('session.gc_maxlifetime', 86400); // 24 horas
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => '',
        'secure' => true,   
        'httponly' => true
    ]);
    session_start();
}

// Headers de seguridad y para prevenir cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

// Validación de sesión de administrador
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Destruir cualquier sesión residual por seguridad
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    // Redirigir al login
    header('Location: index.php?page=login');
    exit();
}