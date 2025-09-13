<?php
// Inicia sesión solo si no hay ninguna activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el administrador está logueado
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php?page=login');
    exit; // Siempre usa exit después de redireccionar
}
?>

<body>
    <div class="container py-5">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">📊 Gestión de Reportes</h1>
        </div>

        <!-- Reportes de Citas -->
        <div class="row mt-4 py-3 justify-content-center">
            <!-- Reporte de Citas -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-chart-line fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title text-white">Reporte de Citas</h5>
                        <p class="card-text text-white">Genera un resumen detallado de todas las citas registradas, incluyendo métricas y estadísticas.</p>
                        <a href="index.php?page=reportes_citas" class="btn btn-primary">Generar Reporte</a>
                    </div>
                </div>
            </div>

            <!-- Reporte de Ingresos -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-dollar-sign fa-3x mb-3 text-info"></i>
                        <h5 class="card-title text-white">Reporte de Ingresos</h5>
                        <p class="card-text text-white">Resumen de ingresos generados por los servicios prestados.</p>
                        <a href="index.php?page=reportes_ingresos" class="btn btn-info">Generar Reporte</a>
                    </div>
                </div>
            </div>

            <!-- Barbero más Solicitado -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-user-tie fa-3x mb-3 text-success"></i>
                        <h5 class="card-title text-white">Barbero más Solicitado</h5>
                        <p class="card-text text-white">Identifica al barbero con mayor cantidad de citas asignadas.</p>
                        <a href="index.php?page=reporte_barberoSolicitado" class="btn btn-success">Generar Reporte</a>
                    </div>
                </div>
            </div>

            <!-- Hrs trabajadas Barbero -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-clock fa-3x mb-3 text-danger"></i>
                        <h5 class="card-title text-white">Hrs trabajadas Barbero</h5>
                        <p class="card-text text-white">Consulta las horas efectivas trabajadas por cada barbero.</p>
                        <a href="index.php?page=reporte_hrBaberos" class="btn btn-danger">Generar Reporte</a>
                    </div>
                </div>
            </div>

            <!-- Servicio más Solicitado -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-scissors fa-3x mb-3 text-warning"></i>
                        <h5 class="card-title text-white">Servicio más Solicitado</h5>
                        <p class="card-text text-white">Descubre cuáles son los servicios más solicitados por los clientes.</p>
                        <a href="index.php?page=reporte_ventas" class="btn btn-warning">Generar Reporte</a>
                    </div>
                </div>
            </div>

            <!-- Reporte de Eventos -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-calendar-alt fa-3x mb-3 text-white"></i>
                        <h5 class="card-title text-white">Reporte de Eventos</h5>
                        <p class="card-text text-white">Ahora puedes saber qué eventos han sido realizados en el año.</p>
                        <a href="index.php?page=reporte_ventas" class="btn" style="background-color: white; color: black;">Generar Reporte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<main>