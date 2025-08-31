<?php
require_once __DIR__ . '/plantillas/header.php';

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: /index.php?page=login");
    exit();
}
?>

<div class="container py-5">
    <div class="card shadow-lg border-0">
        <div class="card-body text-center">
            <h2 class="mb-3">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋</h2>
            <p class="mb-4">Este es tu panel de administración. Gestiona toda la información desde aquí.</p>
            <a href="/area51_barbershop_2025/logout.php" class="btn btn-danger mb-4">Cerrar sesión</a>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Dashboard -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-chart-line fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Dashboard</h5>
                    <p class="card-text">Resumen de métricas, estadísticas y actividad.</p>
                    <a href="/area51_barbershop_2025/app/views/dashboard.php" class="btn btn-primary">Ir al Dashboard</a>
                </div>
            </div>
        </div>

        <!-- Gestión de Citas -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-calendar-check fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">Citas</h5>
                    <p class="card-text">Crear, editar o eliminar citas registradas.</p>
                    <a href="/area51_barbershop_2025/index.php?page=panel" class="btn btn-success">Gestionar Citas</a>
                </div>
            </div>
        </div>

        <!-- Gestión de Servicios -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-cut fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title">Servicios</h5>
                    <p class="card-text">Agregar, editar o eliminar servicios ofrecidos.</p>
                    <a href="/area51_barbershop_2025/index.php?page=servicios" class="btn btn-warning">Gestionar Servicios</a>
                </div>
            </div>
        </div>

        <!-- Gestión de Barberos -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-user-tie fa-3x mb-3 text-info"></i>
                    <h5 class="card-title">Barberos</h5>
                    <p class="card-text">Control total de la plantilla de barberos.</p>
                    <a href="/area51_barbershop_2025/index.php?page=barberos" class="btn btn-info">Gestionar Barberos</a>
                </div>
            </div>
        </div>

        <!-- Administradores -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-users-cog fa-3x mb-3 text-dark"></i>
                    <h5 class="card-title">Administradores</h5>
                    <p class="card-text">Editar información de administradores.</p>
                    <a href="/area51_barbershop_2025/index.php?page=administradores" class="btn btn-dark">Gestionar Admins</a>
                </div>
            </div>
        </div>

        <!-- Reportes -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fa fa-file-alt fa-3x mb-3 text-danger"></i>
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text">Generar reportes PDF/Excel de citas y ventas.</p>
                    <a href="/area51_barbershop_2025/app/views/reportes.php" class="btn btn-danger">Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/plantillas/footer.php'; ?>
