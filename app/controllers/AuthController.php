<?php
// controllers/AuthController.php

// Iniciar sesión si no hay
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir modelo y conexión
require_once __DIR__ . '/../models/Administrador.php';
require_once __DIR__ . '/../../config/database.php';

// Solo procesar si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Buscar el usuario en la BD
    $stmt = $db->prepare("SELECT * FROM administradores WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar que exista y la contraseña
    if ($admin && password_verify($password, $admin['password'])) {
        // Guardar en sesión
        $_SESSION['admin'] = $admin['id_admin'];

        // Redirigir al panel
        header("Location: ../../index.php?page=panel");
        exit;
    } else {
        // Error en login
        $error = "Usuario o contraseña incorrectos";
        include __DIR__ . '/../views/login.php';
        exit;
    }
} else {
    // Si alguien entra directo al archivo, lo mandamos al login
    header("Location: ../../index.php?page=login");
    exit;
}