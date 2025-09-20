<?php
// Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Evitar errores de encabezados enviados
ob_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área 51 - Barbershop</title>
    <!-- Bootstrap y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Icono -->
    <link rel="icon" href="public/img/logo.png">
    <!-- Icono -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap"
        rel="stylesheet">
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <!-- Encabezado -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top" style="margin: 15px;border-radius: 25px;
        border-inline: 2px solid #00ff00;">
        <div class="container-fluid">
            <a class="navbar-brand">
                <img src="public/img/logo.png" alt="Logo Área51_Barbería" class="img-fluid text-white" style="height: 50px;width: 50px;">
                <strong class="text-white">Area 51_Barbershop</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-4">
                        <a class="nav-link text-white" href="/area51_barbershop_2025/index.php?page=home#inicio">Inicio</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link text-white" href="/area51_barbershop_2025/index.php?page=home#nosotros">Nosotros</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link text-white" href="/area51_barbershop_2025/index.php?page=home#servicios">Servicios</a>
                    </li>
                    <li class="nav-item dropdown me-4">
                        <a class="nav-link dropdown-toggle text-white" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Más
                        </a>
                        <ul class="dropdown-menu dropdown-menu-start bg-dark ms-n2">
                            <li><a class="dropdown-item text-white bg-dark" href="/area51_barbershop_2025/index.php?page=home#barberos">Barberos</a></li>
                            <li><a class="dropdown-item text-white bg-dark" href="/area51_barbershop_2025/index.php?page=home#galeria">Galería</a></li>
                            <li><a class="dropdown-item text-white bg-dark" href="/area51_barbershop_2025/index.php?page=home#videos">Videos</a></li>
                            <li><a class="dropdown-item text-white bg-dark" href="/area51_barbershop_2025/index.php?page=home#noticias">Noticias</a></li>
                            <li><a class="dropdown-item text-white bg-dark" href="/area51_barbershop_2025/index.php?page=home#testimonios">Testimonios</a></li>
                            <li><a class="dropdown-item text-white bg-dark" href="/area51_barbershop_2025/index.php?page=home#contacto">Contáctanos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link text-white" href="/area51_barbershop_2025/index.php?page=login">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Botón Scroll hacia abajo y devuelve al inicio -->
    <button id="scrollToTopBtn" class="btn" style="position:fixed; bottom:40px; right:30px; z-index:9999; width: 50px; height:40px;
        display:none; align-items:center; justify-content:center;">
        <i class="fa-solid fa-chevron-up fa-lg"></i>
    </button>

    <!-- Código JS para el Botón Scroll hacia arriba -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btn = document.getElementById("scrollToTopBtn");

            // Mostrar/ocultar el botón al hacer scroll
            window.addEventListener("scroll", function () {
                btn.style.display = (window.scrollY > 200) ? "flex" : "none";
            });

            // Subir instantáneamente sin animación conflictiva
            btn.addEventListener("click", function () {
                window.scrollTo(0, 0);
            });
        });
    </script>

    <!-- Ajuste para que el navbar fijo no tape las secciones -->
    <style>
        section {
            scroll-margin-top: 100px; /* altura de tu navbar */
        }
    </style>
<main>