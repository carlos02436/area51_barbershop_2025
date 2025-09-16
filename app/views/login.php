<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Si ya está logueado, ir directo al panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
  header("Location: index.php?page=panel");
  exit();
}

// Si envió el formulario de login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  require_once __DIR__ . "/../../config/database.php";

  $usuario = $_POST['usuario'];
  $password = $_POST['password'];

  // Validar en BD
  $stmt = $db->prepare("SELECT * FROM administradores WHERE usuario = :usuario AND password = :password");
  $stmt->bindParam(":usuario", $usuario);
  $stmt->bindParam(":password", $password); // ⚠️ Esto es inseguro (texto plano)
  $stmt->execute();
  $admin = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($admin) {
    // Guardar sesión con más datos
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id']       = $admin['id_admin'];
    $_SESSION['admin_usuario']  = $admin['usuario'];
    $_SESSION['admin_nombre']   = $admin['nombre'];  // ✅ Aquí guardamos el nombre
    $_SESSION['admin_email']    = $admin['email'];
    $_SESSION['admin_img']      = $admin['img_admin']; // ✅ foto si quieres usarla

    // Si marcó "Recuérdame" -> guardar en cookies por 7 días
    if (isset($_POST['rememberMe'])) {
      setcookie("usuario", $usuario, time() + (7 * 24 * 60 * 60), "/");
      setcookie("password", $password, time() + (7 * 24 * 60 * 60), "/");
    } else {
      // Si no, borrar cookies
      setcookie("usuario", "", time() - 3600, "/");
      setcookie("password", "", time() - 3600, "/");
    }

    header("Location: index.php?page=panel");
    exit();
  } else {
    $error = "Usuario o contraseña incorrectos.";
  }
}

// Si hay cookies, precargar en inputs
$usuario_cookie = $_COOKIE['usuario'] ?? '';
$password_cookie = $_COOKIE['password'] ?? '';
$error = $error ?? null;