<?php
// Incluir cabecera y conexión
require_once __DIR__ . '../plantillas/header.php';
require_once __DIR__ . '/../../config/database.php'; // Ajusta si es necesario

// === Estadísticas ===

// Total clientes
$stmt = $db->query("SELECT COUNT(*) AS total FROM clientes");
$clientesCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Citas de hoy
$stmt = $db->prepare("SELECT COUNT(*) AS total FROM citas WHERE fecha_cita = CURDATE()");
$stmt->execute();
$citasCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Barberos (sin estado porque tu tabla no tiene esa columna)
$stmt = $db->query("SELECT COUNT(*) AS total FROM barberos");
$barberosCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Ingresos de hoy
$stmt = $db->prepare("
    SELECT SUM(s.precio) AS total 
    FROM citas c
    INNER JOIN servicios s ON c.id_servicio = s.id_servicio
    WHERE c.fecha_cita = CURDATE()
");
$stmt->execute();
$ingresosHoy = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// === Próximas citas ===
$stmt = $db->prepare("
    SELECT 
        c.id_cita, 
        cl.nombre AS cliente, 
        b.nombre AS barbero, 
        c.fecha_cita, 
        c.hora_cita, 
        s.nombre AS servicio
    FROM citas c
    JOIN clientes cl ON c.id_cliente = cl.id_cliente
    JOIN barberos b ON c.id_barbero = b.id_barbero
    JOIN servicios s ON c.id_servicio = s.id_servicio
    WHERE c.fecha_cita >= CURDATE()
    ORDER BY c.fecha_cita, c.hora_cita
    LIMIT 10
");
$stmt->execute();
$proximasCitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// === Servicios más solicitados ===
$stmt = $db->query("
    SELECT s.nombre, COUNT(c.id_cita) AS cantidad
    FROM citas c
    JOIN servicios s ON c.id_servicio = s.id_servicio
    GROUP BY s.nombre
    ORDER BY cantidad DESC
    LIMIT 5
");
$serviciosPopulares = $stmt->fetchAll(PDO::FETCH_ASSOC);

// === Últimos clientes registrados ===
$stmt = $db->query("
    SELECT nombre, fecha_registro
    FROM clientes
    ORDER BY fecha_registro DESC
    LIMIT 5
");
$ultimosClientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid bg-light my-5 py-5">
    <h1 class="text-center fw-bold mb-5 display-4 text-primary">
        📊 Dashboard General
    </h1>

    <!-- Tarjetas de resumen -->
    <div class="row g-4 mb-5">
        <?php
        $stats = [
            ['title' => 'Clientes', 'value' => htmlspecialchars($clientesCount), 'color' => 'primary', 'desc' => 'Total registrados'],
            ['title' => 'Citas', 'value' => htmlspecialchars($citasCount), 'color' => 'success', 'desc' => 'Hoy'],
            ['title' => 'Barberos', 'value' => htmlspecialchars($barberosCount), 'color' => 'warning', 'desc' => 'Registrados'],
            ['title' => 'Ingresos', 'value' => '$' . number_format($ingresosHoy, 2, ',', '.'), 'color' => 'danger', 'desc' => 'Hoy'],
        ];

        foreach ($stats as $stat): ?>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted fw-semibold"><?= $stat['title'] ?></h6>
                        <h2 class="fw-bold text-<?= $stat['color'] ?>"><?= $stat['value'] ?></h2>
                        <p class="small text-secondary"><?= $stat['desc'] ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Próximas citas -->
    <div class="card shadow-sm border-0 rounded-4 mb-5">
        <div class="card-header bg-dark text-white fw-bold rounded-top-4">
            📅 Próximas Citas
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Barbero</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Servicio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($proximasCitas)): ?>
                            <?php foreach ($proximasCitas as $cita): ?>
                                <tr>
                                    <td><?= htmlspecialchars($cita['cliente']) ?></td>
                                    <td><?= htmlspecialchars($cita['barbero']) ?></td>
                                    <td><?= htmlspecialchars($cita['fecha_cita']) ?></td>
                                    <td><?= htmlspecialchars($cita['hora_cita']) ?></td>
                                    <td><?= htmlspecialchars($cita['servicio']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No hay próximas citas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Servicios más solicitados y últimos clientes -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-info text-white fw-bold rounded-top-4">
                    🔥 Servicios más solicitados
                </div>
                <div class="card-body">
                    <?php if (!empty($serviciosPopulares)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($serviciosPopulares as $servicio): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($servicio['nombre']) ?>
                                    <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($servicio['cantidad']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0">No hay datos</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-warning text-dark fw-bold rounded-top-4">
                    👤 Últimos clientes registrados
                </div>
                <div class="card-body">
                    <?php if (!empty($ultimosClientes)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($ultimosClientes as $cliente): ?>
                                <li class="list-group-item">
                                    <?= htmlspecialchars($cliente['nombre']) ?>
                                    <span class="text-muted">(<?= htmlspecialchars($cliente['fecha_registro']) ?>)</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0">No hay nuevos clientes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '../plantillas/footer.php'; ?>