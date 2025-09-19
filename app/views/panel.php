<?php
// VERIFICACIÓN DE SEGURIDAD - INICIO DE SESIÓN
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si está logueado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /area51_barbershop_2025/index.php?page=login');
    exit();
}

// Headers de seguridad para prevenir caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

// Regenerar ID de sesión periódicamente para seguridad
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutos
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Timeout de sesión (30 minutos de inactividad)
$timeout = 1800; // 30 minutos
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header('Location: /area51_barbershop_2025/index.php?page=login&msg=session_expired');
    exit();
}
$_SESSION['last_activity'] = time();
?>
<body>
    <!--  Panel de Control HTML (Bootstrap) -->
    <div class="container py-3 text-center" style="margin:130px auto;">
        <div class="card shadow-lg border-0">
            <div class="card-body text-center">
                <h2 class="mb-3 text-white">Bienvenid@ a su Panel de Control 👋</h2>
                <p class="mb-4 text-white">Este es su panel de administración, el cual le ayudará a gestionar toda la información de su negocio desde aquí.</p>
            </div>
        </div>

        <!-- Dashboard y botones -->
        <div class="row mt-4">
            <!-- Dashboard -->
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
                        <p class="card-text text-white">Editar o eliminar citas registradas.</p>
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
                        <p class="card-text text-white">Generar reportes PDF de citas y servicios.</p>
                        <a href="/area51_barbershop_2025/index.php?page=reportes" class="btn btn-danger">Ver Reportes</a>
                    </div>
                </div>
            </div>

            <!-- youtube -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fab fa-youtube fa-3x mb-3 text-danger"></i>
                        <h5 class="card-title text-white">YouTube</h5>
                        <p class="card-text text-white">Actualiza tus videos de YouTube.</p>
                        <a href="/area51_barbershop_2025/index.php?page=youtube" class="btn btn-danger">Actualizar</a>
                    </div>
                </div>
            </div>

            <!-- TikTok -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fab fa-tiktok fa-3x mb-3 text-black"></i>
                        <h5 class="card-title text-white">TikTok</h5>
                        <p class="card-text text-white">Actualiza tus videos de TikTok.</p>
                        <a href="/area51_barbershop_2025/index.php?page=tiktok" class="btn btn-dark">Actualizar</a>
                    </div>
                </div>
            </div>

            <!-- Gestión SIAU -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-user-tie fa-3x mb-3 text-info"></i>
                        <h5 class="card-title text-white">SIAU</h5>
                        <p class="card-text text-white">Acciones de mejoras para el negocio.</p>
                        <a href="/area51_barbershop_2025/index.php?page=PQRS" class="btn btn-info">Gestionar PQRS</a>
                    </div>
                </div>
            </div>

            <!-- Sección Noticias -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-file-alt fa-3x mb-3 text-info"></i>
                        <h5 class="card-title text-white">Noticias</h5>
                        <p class="card-text text-white">Actualiza tus noticias y/o eventos.</p>
                        <a href="/area51_barbershop_2025/index.php?page=noticias" class="btn btn-info">Actualizar</a>
                    </div>
                </div>
            </div>

            <!-- Sección Testimonios -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-3x mb-3 text-warning">💬</i>
                        <h5 class="card-title text-white">Testimonios</h5>
                        <p class="card-text text-white">Actualiza tus noticias y/o eventos.</p>
                        <a href="/area51_barbershop_2025/index.php?page=testimonios" class="btn btn-warning">Ver Testimonios</a>
                    </div>
                </div>
            </div>

            <!-- Clientes -->
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fa fa-3x mb-3 text-danger">👥</i>
                        <h5 class="card-title text-white">Clientes</h5>
                        <p class="card-text text-white">Accede a tus clientes.</p>
                        <a href="/area51_barbershop_2025/index.php?page=Cliente" class="btn btn-danger">Gestionar Clientes</a>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <a href="/area51_barbershop_2025/index.php?page=logout" class="btn btn-danger" style="display: block;width:200px;margin: 20px auto;">
                Cerrar sesión
            </a>
        </div>
    </div>

    <script>
        // Seguridad JS - solo cambiar rutas a login mediante index.php?page=login
        window.onload = function() {
            if (window.performance && window.performance.navigation.type === 2) {
                window.location.replace('/area51_barbershop_2025/index.php?page=login');
            }

            checkSession();
            setInterval(checkSession, 60000);

            let lastActivity = Date.now();
            const timeoutDuration = 30 * 60 * 1000;

            ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(event => {
                document.addEventListener(event, function() {
                    lastActivity = Date.now();
                }, false);
            });

            setInterval(function() {
                if (Date.now() - lastActivity > timeoutDuration) {
                    alert('Su sesión ha expirado por inactividad. Será redirigido al login.');
                    window.location.replace('/area51_barbershop_2025/index.php?page=login&msg=timeout');
                }
            }, 60000);
        };

        function checkSession() {
            fetch('/area51_barbershop_2025/index.php?page=check_session', {
                    method: 'GET',
                    cache: 'no-cache',
                    headers: {
                        'Cache-Control': 'no-cache',
                        'Pragma': 'no-cache'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.logged_in) {
                        window.location.replace('/area51_barbershop_2025/index.php?page=login');
                    }
                })
                .catch(error => {
                    console.error('Error verificando sesión:', error);
                    window.location.replace('/area51_barbershop_2025/index.php?page=login');
                });
        }

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) window.location.reload();
        });

        window.addEventListener('popstate', function(event) {
            checkSession();
        });
    </script>
<main>