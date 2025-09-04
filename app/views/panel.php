<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Evitar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");

// Verificar login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /area51_barbershop_2025/index.php?page=login');
    exit();
}
?>
<!--  Panel de Control -->
<div class="container py-3 text-center" style="margin:130px auto;">
    <div class="card shadow-lg border-0">
        <div class="card-body text-center">
            <h2 class="mb-3 text-white">Bienvenid@ a su Panel de Control 👋</h2>
            <p class="mb-4 text-white">Este es su panel de administración, el cual le ayudará a gestionar toda la información de su negocio desde aquí.</p>
        </div>
    </div>

    <!-- Dashboard -->
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-chart-line fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title text-white">Dashboard</h5>
                    <p class="card-text text-white">Resumen de métricas, estadísticas y actividad.</p>
                    <a href="/area51_barbershop_2025/index.php?page=dashboard" class="btn btn-primary">Ir al Dashboard</a>
                </div>
            </div>
        </div>

        <!-- Gestión de Citas -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-calendar-check fa-3x mb-3 text-success"></i>
                    <h5 class="card-title text-white">Citas</h5>
                    <p class="card-text text-white">Crear, editar o eliminar citas registradas.</p>
                    <a href="/area51_barbershop_2025/index.php?page=citas" class="btn btn-success">Gestionar Citas</a>
                </div>
            </div>
        </div>

        <!-- Gestión de Servicios -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-cut fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title text-white">Servicios</h5>
                    <p class="card-text text-white">Agregar, editar o eliminar servicios ofrecidos.</p>
                    <a href="/area51_barbershop_2025/index.php?page=servicios" class="btn btn-warning">Gestionar Servicios</a>
                </div>
            </div>
        </div>

        <!-- Gestión de Barberos -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-user-tie fa-3x mb-3 text-info"></i>
                    <h5 class="card-title text-white">Barberos</h5>
                    <p class="card-text text-white">Control total de la plantilla de barberos.</p>
                    <a href="/area51_barbershop_2025/index.php?page=barberos" class="btn btn-info">Gestionar Barberos</a>
                </div>
            </div>
        </div>

        <!-- Administradores -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-users-cog fa-3x mb-3 text-dark"></i>
                    <h5 class="card-title text-white">Administradores</h5>
                    <p class="card-text text-white">Editar información de administradores.</p>
                    <a href="/area51_barbershop_2025/index.php?page=administradores" class="btn btn-dark">Gestionar Admins</a>
                </div>
            </div>
        </div>

        <!-- Reportes -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-file-alt fa-3x mb-3 text-danger"></i>
                    <h5 class="card-title text-white">Reportes</h5>
                    <p class="card-text text-white">Generar reportes PDF/Excel de citas y ventas.</p>
                    <a href="/area51_barbershop_2025/index.php?page=reportes" class="btn btn-danger">Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <a href="/area51_barbershop_2025/app/views/auth/logout.php" class="btn btn-danger" style="display: block;width:200px;margin: 20px auto;">
            Cerrar sesión
        </a>
    </div>
</div>

<script>
// Sistema de seguridad JavaScript - MVC corregido
window.onload = function() {
    // Prevenir navegación con botones del navegador
    if (window.performance && window.performance.navigation.type === 2) {
        window.location.replace('/area51_barbershop_2025/index.php?page=login');
    }

    // Verificar sesión al cargar
    checkSession();

    // Verificar sesión cada 60 segundos
    setInterval(checkSession, 60000);

    // Detectar inactividad (30 minutos)
    let lastActivity = Date.now();
    const timeoutDuration = 30 * 60 * 1000; // 30 minutos

    ['mousedown','mousemove','keypress','scroll','touchstart','click'].forEach(event => {
        document.addEventListener(event, () => { lastActivity = Date.now(); }, false);
    });

    // Timeout por inactividad
    setInterval(() => {
        if (Date.now() - lastActivity > timeoutDuration) {
            alert('Su sesión ha expirado por inactividad. Será redirigido al login.');
            window.location.replace('/area51_barbershop_2025/index.php?page=login&msg=timeout');
        }
    }, 60000);
};

// Función para verificar si la sesión sigue activa
function checkSession() {
    fetch('/area51_barbershop_2025/app/views/auth/check_session.php', {
        method: 'GET',
        cache: 'no-cache',
        headers: { 'Cache-Control': 'no-cache', 'Pragma': 'no-cache' }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.logged_in) {
            // Redirigir al enrutador principal, no a login.php directo
            window.location.replace('/area51_barbershop_2025/index.php?page=login');
        }
    })
    .catch(() => {
        window.location.replace('/area51_barbershop_2025/index.php?page=login');
    });
}

// Prevenir cache con botones atrás/adelante
window.addEventListener('pageshow', function(event) {
    if (event.persisted) window.location.reload();
});

// Manejar eventos de navegación
window.addEventListener('popstate', checkSession);
</script>