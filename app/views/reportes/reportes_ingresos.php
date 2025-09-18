<?php
require_once __DIR__ . '/../auth_admin.php';

// Verifica si el administrador está logueado
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../../../config/database.php';

// ==========================
// Filtros (inicializar variables)
// ==========================
$anio    = $_GET['anio'] ?? date('Y');
$mes     = $_GET['mes'] ?? '';
$dia     = $_GET['dia'] ?? '';

// Construir condiciones dinámicas
$condiciones = [];
$params = [];

if (!empty($anio)) {
    $condiciones[] = "YEAR(fecha_inicio) = :anio";
    $params[':anio'] = $anio;
}
if (!empty($mes)) {
    $condiciones[] = "MONTH(fecha_inicio) = :mes";
    $params[':mes'] = $mes;
}
if (!empty($dia)) {
    $condiciones[] = "DAY(fecha_inicio) = :dia";
    $params[':dia'] = $dia;
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

// ==========================
// Consultas
// ==========================
// Total de ingresos
$sqlTotal = "SELECT COALESCE(SUM(total_ingresos),0) AS totalIngresos, COALESCE(SUM(total_citas),0) AS totalCitas
             FROM ingresos $where";
$stmt = $db->prepare($sqlTotal);
$stmt->execute($params);
$total = $stmt->fetch(PDO::FETCH_ASSOC);
$totalIngresos = $total['totalIngresos'] ?? 0;
$totalCitas = $total['totalCitas'] ?? 0;

// Listado de ingresos
$sqlIngresos = "SELECT id_ingreso, periodo, fecha_inicio, fecha_fin, total_ingresos, total_citas, creado_en
                FROM ingresos
                $where
                ORDER BY fecha_inicio DESC";
$stmt = $db->prepare($sqlIngresos);
$stmt->execute($params);
$ingresos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
    <div class="container py-5" style="margin-top:20px;">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=reportes" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> 
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">📅 Reporte de Ingresos</h1>
        </div>

        <!-- Filtros -->
        <form method="GET" action="index.php" 
            class="d-flex justify-content-center align-items-center gap-3 mb-4 shadow-sm border-0 rounded-4 p-3" 
            style="background: rgba(0,0,0,0.3); border-radius: 15px; flex-wrap: wrap;">

            <input type="hidden" name="page" value="reportes_ingresos">

            <?php $ancho = '150px'; // ancho uniforme ?>

            <!-- Año -->
            <div class="d-flex flex-column align-items-center">
                <label class="form-label text-white mb-1">Año</label>
                <input type="number" name="anio" class="form-control text-center" style="width: <?= $ancho ?>;" value="<?= htmlspecialchars($anio) ?>">
            </div>

            <!-- Mes -->
            <div class="d-flex flex-column align-items-center">
                <label class="form-label text-white mb-1">Mes</label>
                <select name="mes" class="form-select text-center" style="width: <?= $ancho ?>;">
                    <option value="">--</option>
                    <?php 
                    $meses = [
                        1=>"Enero", 2=>"Febrero", 3=>"Marzo", 4=>"Abril", 5=>"Mayo", 6=>"Junio", 
                        7=>"Julio", 8=>"Agosto", 9=>"Septiembre", 10=>"Octubre", 11=>"Noviembre", 12=>"Diciembre"
                    ];
                    foreach ($meses as $num => $nombre): ?>
                        <option value="<?= $num ?>" <?= ((string)$mes === (string)$num) ? 'selected' : '' ?>>
                            <?= $nombre ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Día -->
            <div class="d-flex flex-column align-items-center">
                <label class="form-label text-white mb-1">Día</label>
                <input type="number" name="dia" min="1" max="31" class="form-control text-center" style="width: <?= $ancho ?>;" value="<?= htmlspecialchars($dia) ?>">
            </div>

            <!-- Botones alineados horizontalmente -->
            <div class="d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-neon" style="width: <?= $ancho ?>;">Filtrar</button>
                <a href="index.php?page=reportes_ingresos" class="btn btn-secondary" style="width: <?= $ancho ?>;">Limpiar</a>
            </div>
        </form>

    <!-- Métricas -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6 class="text-white">Total de Ingresos</h6>
                    <h2 class="fw-bold text-white"><?= number_format($totalIngresos,0,',','.') ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6 class="text-white">Total de Citas</h6>
                    <h2 class="fw-bold text-white"><?= $totalCitas ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-dark text-white fw-bold text-center">
            📋 Listado de Ingresos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bg-dark table-hover align-middle mb-0">
                    <thead class="bg-dark">
                        <tr>
                            <th>ID</th>
                            <th>Periodo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Total Ingresos</th>
                            <th>Total Citas</th>
                            <th>Registrado En</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ingresos)): ?>
                            <?php foreach ($ingresos as $ingreso): ?>
                                <tr>
                                    <td><?= $ingreso['id_ingreso'] ?></td>
                                    <td><?= htmlspecialchars($ingreso['periodo']) ?></td>
                                    <td><?= $ingreso['fecha_inicio'] ?></td>
                                    <td><?= $ingreso['fecha_fin'] ?></td>
                                    <td><?= number_format($ingreso['total_ingresos'],0,',','.') ?></td>
                                    <td><?= $ingreso['total_citas'] ?></td>
                                    <td><?= $ingreso['creado_en'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No hay ingresos para el período seleccionado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Botón PDF -->
    <div class="container py-2" style="margin-top:10px;">
        <div class="d-flex justify-content-end">
            <a href="app/views/reportes/generar_pdf_ingresos.php?anio=<?= urlencode($anio) ?>&mes=<?= urlencode($mes) ?>&dia=<?= urlencode($dia) ?>" 
               target="_blank" 
               class="btn btn-neon float-end">
               📄 Descargar PDF
            </a>
        </div>
    </div>
</div>
<main>