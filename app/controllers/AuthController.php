<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Administrador.php';
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM administradores WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $admin = $stmt->fetch();

    if ($admin && $password === $admin['password']) { // Cambiar a password_verify si usas hash
        $_SESSION['admin'] = $admin['id_admin'];
        header("Location: ../../index.php?page=panel");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
        include __DIR__ . '/../views/login.php';
    }
} else {
    header("Location: ../../index.php?page=login");
    exit;
}