<?php
// index.php (COMPLETO)
session_start();

// Conexión PDO (define $db)
require_once __DIR__ . '/config/database.php';

// NOTA: NO incluir modelos globalmente aquí para evitar efectos secundarios.
// Incluye los modelos/contollers solo en las vistas o controladores que los necesiten.

// ====== Ruteo ======
$page = $_GET['page'] ?? 'login';
$error = $error ?? null;

// ====== Procesar POST del login (antes de enviar salida) ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'login') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($usuario === '' || $password === '') {
        $error = "Por favor complete usuario y contraseña.";
    } else {
        try {
            $stmt = $db->prepare("SELECT * FROM administradores WHERE usuario = :usuario LIMIT 1");
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && isset($admin['password']) && $admin['password'] === $password) {
                // Login correcto (texto plano). Si migras a hash usa password_verify.
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['admin_usuario'] = $admin['usuario'];
                $_SESSION['last_activity'] = time();

                header('Location: index.php?page=panel');
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            // Si hay error de BD lo mostramos en $error (solo en desarrollo)
            $error = "Error de base de datos: " . $e->getMessage();
        }
    }
}

// ====== RENDER (header + view + footer) ======
switch ($page) {

    case 'login':
        // Si ya logueado -> panel
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header('Location: index.php?page=panel');
            exit;
        }
        include __DIR__ . '/app/views/plantillas/header.php';
        include __DIR__ . '/app/views/login.php';   // login.php usará $error si existe
        include __DIR__ . '/app/views/plantillas/footer.php';
        break;

    case 'logout':
        // Destruir sesión de forma segura
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: index.php?page=login');
        exit;

    case 'panel':
        // PROTECCIÓN: si no está logueado, llevar al login
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: index.php?page=login');
            exit;
        }

        // Timeout de inactividad (30 minutos)
        $timeout = 1800;
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
            // destruir sesión y redirigir
            $_SESSION = [];
            session_destroy();
            header('Location: index.php?page=login');
            exit;
        }
        $_SESSION['last_activity'] = time();

        // Evitar caché para que atrás/adelante no muestre panel después de logout
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

        include __DIR__ . '/app/views/plantillas/header.php';
        include __DIR__ . '/app/views/panel.php';
        include __DIR__ . '/app/views/plantillas/footer.php';
        break;

    case 'home':
        include __DIR__ . '/app/views/plantillas/header.php';
        include __DIR__ . '/app/views/home.php';
        include __DIR__ . '/app/views/plantillas/footer.php';
        break;

    default:
        include __DIR__ . '/app/views/plantillas/header.php';
        echo "<section class='container py-5 text-center'>
                <h2 class='text-danger'>404 - Página no encontrada</h2>
                <p>Lo sentimos, la página que buscas no existe.</p>
                <a href='index.php' class='btn btn-primary mt-3'>Volver al inicio</a>
              </section>";
        include __DIR__ . '/app/views/plantillas/footer.php';
        break;
}