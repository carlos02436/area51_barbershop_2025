<?php
require_once __DIR__ . '/../auth_admin.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../../../config/database.php';

// ==========================
// Filtros
// ==========================
$anio = $_GET['anio'] ?? date('Y');
$mes  = $_GET['mes'] ?? '';
$dia  = $_GET['dia'] ?? '';

$condiciones = [];
$params = [];
if (!empty($anio)) { $condiciones[] = "YEAR(c.fecha_cita) = :anio"; $params[':anio'] = $anio; }
if (!empty($mes))  { $condiciones[] = "MONTH(c.fecha_cita) = :mes"; $params[':mes'] = $mes; }
if (!empty($dia))  { $condiciones[] = "DAY(c.fecha_cita) = :dia"; $params[':dia'] = $dia; }
$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

// ==========================
// Barbero más solicitado
// ==========================
$sqlBarbero = "SELECT b.nombre AS barbero, COUNT(c.id_cita) AS total_citas
               FROM citas c
               INNER JOIN barberos b ON c.id_barbero = b.id_barbero
               $where
               GROUP BY c.id_barbero
               ORDER BY total_citas DESC
               LIMIT 1";
$stmt = $db->prepare($sqlBarbero);
$stmt->execute($params);
$barberoSolicitado = $stmt->fetch(PDO::FETCH_ASSOC);
$barberoNombre = $barberoSolicitado['barbero'] ?? 'No hay datos';
$barberoCitas = $barberoSolicitado['total_citas'] ?? 0;

// ==========================
// Horas trabajadas por barbero (1 hora por cita)
// ==========================
$sqlHoras = "SELECT b.nombre AS barbero, COUNT(c.id_cita) AS total_citas, COUNT(c.id_cita)*1 AS total_horas
             FROM citas c
             INNER JOIN barberos b ON c.id_barbero = b.id_barbero
             $where
             GROUP BY c.id_barbero
             ORDER BY total_horas DESC";
$stmt = $db->prepare($sqlHoras);
$stmt->execute($params);
$horasTrabajadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================
// Listado de citas con nombre de cliente y servicio
// ==========================
$sqlCitas = "SELECT c.id_cita, b.nombre AS barbero, cl.nombre AS cliente_nombre, cl.apellido AS cliente_apellido, s.nombre AS servicio, c.fecha_cita, c.hora_cita, c.estado
             FROM citas c
             INNER JOIN barberos b ON c.id_barbero = b.id_barbero
             INNER JOIN clientes cl ON c.id_cliente = cl.id_cliente
             INNER JOIN servicios s ON c.id_servicio = s.id_servicio
             $where
             ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
$stmt = $db->prepare($sqlCitas);
$stmt->execute($params);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
    <div class="container py-5" style="margin-top:20px;">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=reportes" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> 
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">📊 Barbero - Horas y Citas</h1>
        </div>

        <!-- Filtros -->
        <form method="GET" action="index.php" class="d-flex justify-content-center align-items-center gap-3 mb-4 shadow-sm border-0 rounded-4 p-3" style="background: rgba(0,0,0,0.3); flex-wrap: wrap;">
            <input type="hidden" name="page" value="reportes_hrbarbero">
            <?php $ancho = '150px'; ?>
            <div class="d-flex flex-column align-items-center">
                <label class="form-label text-white mb-1">Año</label>
                <input type="number" name="anio" class="form-control text-center" style="width: <?= $ancho ?>;" value="<?= htmlspecialchars($anio) ?>">
            </div>
            <div class="d-flex flex-column align-items-center">
                <label class="form-label text-white mb-1">Mes</label>
                <select name="mes" class="form-select text-center" style="width: <?= $ancho ?>;">
                    <option value="">--</option>
                    <?php 
                    $meses = [
                        1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",
                        7=>"Julio",8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
                    ];
                    foreach ($meses as $num => $nombre): ?>
                        <option value="<?= $num ?>" <?= ((string)$mes === (string)$num) ? 'selected' : '' ?>><?= $nombre ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-flex flex-column align-items-center">
                <label class="form-label text-white mb-1">Día</label>
                <input type="number" name="dia" min="1" max="31" class="form-control text-center" style="width: <?= $ancho ?>;" value="<?= htmlspecialchars($dia) ?>">
            </div>
            <div class="d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-neon" style="width: <?= $ancho ?>;">Filtrar</button>
                <a href="index.php?page=reportes_hrbarbero" class="btn btn-secondary" style="width: <?= $ancho ?>;">Limpiar</a>
            </div>
        </form>

        <!-- Métricas -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card text-center shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h6 class="text-white">Barbero Más Solicitado</h6>
                        <h3 class="fw-bold text-white"><?= htmlspecialchars($barberoNombre) ?></h3>
                        <p class="text-white mb-0">Citas: <?= $barberoCitas ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Horas trabajadas -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-dark text-white fw-bold text-center">
                ⏱ Horas Trabajadas por Barbero
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bg-dark table-hover align-middle mb-0">
                        <thead class="bg-dark">
                            <tr>
                                <th>Barbero</th>
                                <th>Total Citas</th>
                                <th>Horas Trabajadas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($horasTrabajadas)): ?>
                                <?php foreach ($horasTrabajadas as $h): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($h['barbero']) ?></td>
                                        <td><?= $h['total_citas'] ?></td>
                                        <td><?= $h['total_horas'] ?> horas</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted py-3">No hay citas para el período seleccionado</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Listado de citas -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-dark text-white fw-bold text-center">
                📋 Listado de Citas
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bg-dark table-hover align-middle mb-0">
                        <thead class="bg-dark">
                            <tr>
                                <th>ID</th>
                                <th>Barbero</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($citas)): ?>
                                <?php foreach ($citas as $cita): ?>
                                    <tr>
                                        <td><?= $cita['id_cita'] ?></td>
                                        <td><?= htmlspecialchars($cita['barbero']) ?></td>
                                        <td><?= htmlspecialchars($cita['cliente_nombre'].' '.$cita['cliente_apellido']) ?></td>
                                        <td><?= htmlspecialchars($cita['servicio']) ?></td>
                                        <td><?= $cita['fecha_cita'] ?></td>
                                        <td><?= $cita['hora_cita'] ?></td>
                                        <td><?= $cita['estado'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center text-muted py-3">No hay citas para el período seleccionado</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PDF -->
        <div class="d-flex justify-content-end mb-4">
            <a href="app/views/reportes/generar_pdf_hr.php?anio=<?= urlencode($anio) ?>&mes=<?= urlencode($mes) ?>&dia=<?= urlencode($dia) ?>" target="_blank" class="btn btn-neon">📄 Descargar PDF</a>
        </div>
    </div>
<main>