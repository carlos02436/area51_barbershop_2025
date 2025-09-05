<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) header('Location: index.php?page=login');
?>
<body>
    <div class="container mt-5">
        <h2 class="text-white mb-4">Reportes</h2>
        <p class="text-white">Seleccione el tipo de reporte que desea generar:</p>
        <a href="reporte_citas.php" class="btn btn-danger mb-2">Reporte de Citas</a>
        <a href="reporte_ventas.php" class="btn btn-danger mb-2">Reporte de Ventas</a>
    </div>
<main>