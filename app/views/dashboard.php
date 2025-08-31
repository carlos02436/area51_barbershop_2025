<?php require 'app/views/plantillas/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Panel de Administración</h1>
    <div class="row">
        <!-- Tarjetas de resumen -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Clientes</h5>
                    <h2 class="display-5">
                        <?= htmlspecialchars($clientesCount ?? 0) ?>
                    </h2>
                    <p class="card-text text-muted">Total registrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Citas</h5>
                    <h2 class="display-5">
                        <?= htmlspecialchars($citasCount ?? 0) ?>
                    </h2>
                    <p class="card-text text-muted">Hoy</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Barberos</h5>
                    <h2 class="display-5">
                        <?= htmlspecialchars($barberosCount ?? 0) ?>
                    </h2>
                    <p class="card-text text-muted">Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Ingresos</h5>
                    <h2 class="display-5">
                        $<?= number_format($ingresosHoy ?? 0, 2, ',', '.') ?>
                    </h2>
                    <p class="card-text text-muted">Hoy</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Diagramas y estadísticas -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    Gráfico de Citas por Día (Última Semana)
                </div>
                <div class="card-body">
                    <canvas id="citasPorDiaChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    Distribución de Servicios
                </div>
                <div class="card-body">
                    <canvas id="serviciosChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de próximas citas -->
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            Próximas Citas
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
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
                                    <td><?= htmlspecialchars($cita['fecha']) ?></td>
                                    <td><?= htmlspecialchars($cita['hora']) ?></td>
                                    <td><?= htmlspecialchars($cita['servicio']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay próximas citas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sección de estadísticas rápidas -->
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    Servicios más solicitados
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
                        <p class="text-muted mb-0">No hay datos de servicios.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    Últimos clientes registrados
                </div>
                <div class="card-body">
                    <?php if (!empty($ultimosClientes)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($ultimosClientes as $cliente): ?>
                                <li class="list-group-item">
                                    <?= htmlspecialchars($cliente['nombre']) ?> <span class="text-muted">(<?= htmlspecialchars($cliente['fecha_registro']) ?>)</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0">No hay nuevos clientes.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos para el gráfico de citas por día
    const citasPorDiaLabels = <?= json_encode($citasPorDiaLabels ?? []) ?>;
    const citasPorDiaData = <?= json_encode($citasPorDiaData ?? []) ?>;

    const ctxCitas = document.getElementById('citasPorDiaChart').getContext('2d');
    new Chart(ctxCitas, {
        type: 'line',
        data: {
            labels: citasPorDiaLabels,
            datasets: [{
                label: 'Citas',
                data: citasPorDiaData,
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Datos para el gráfico de servicios
    const serviciosLabels = <?= json_encode($serviciosLabels ?? []) ?>;
    const serviciosData = <?= json_encode($serviciosData ?? []) ?>;

    const ctxServicios = document.getElementById('serviciosChart').getContext('2d');
    new Chart(ctxServicios, {
        type: 'doughnut',
        data: {
            labels: serviciosLabels,
            datasets: [{
                data: serviciosData,
                backgroundColor: [
                    '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#20c997'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>

<?php require 'app/views/plantillas/footer.php'; ?>