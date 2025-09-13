<?php
// Inicia sesión solo si no hay ninguna activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el administrador está logueado
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../../../config/database.php';

// ==========================
// Filtros (inicializar todas las variables para evitar warnings)
// ==========================
$anio    = $_GET['anio']    ?? date('Y');
$mes     = $_GET['mes']     ?? '';
$dia     = $_GET['dia']     ?? '';
$cliente = trim($_GET['cliente'] ?? '');
$barbero = trim($_GET['barbero'] ?? '');

// Construir condiciones dinámicas
$condiciones = [];
$params = [];

// filtros por fecha
if (!empty($anio)) {
    $condiciones[] = "YEAR(c.fecha_cita) = :anio";
    $params[':anio'] = $anio;
}
if (!empty($mes)) {
    $condiciones[] = "MONTH(c.fecha_cita) = :mes";
    $params[':mes'] = $mes;
}
if (!empty($dia)) {
    $condiciones[] = "DAY(c.fecha_cita) = :dia";
    $params[':dia'] = $dia;
}

// filtro por cliente (nombre, apellido o ambos)
if (!empty($cliente)) {
    $condiciones[] = "(cl.nombre LIKE :cliente 
                    OR cl.apellido LIKE :cliente 
                    OR CONCAT(cl.nombre, ' ', cl.apellido) LIKE :cliente)";
    $params[':cliente'] = '%' . $cliente . '%';
}

// filtro por barbero
if (!empty($barbero)) {
    $condiciones[] = "b.nombre LIKE :barbero";
    $params[':barbero'] = '%' . $barbero . '%';
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

// ==========================
// Consultas
// ==========================
// Total de citas (con joins para poder filtrar por cliente/barbero)
$sqlTotal = "
    SELECT COUNT(*) AS total
    FROM citas c
    JOIN clientes cl ON c.id_cliente = cl.id_cliente
    JOIN barberos b ON c.id_barbero = b.id_barbero
    JOIN servicios s ON c.id_servicio = s.id_servicio
    $where
";
$stmt = $db->prepare($sqlTotal);
$stmt->execute($params);
$totalCitas = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Listado de citas
$sqlCitas = "
    SELECT 
        c.id_cita,
        cl.nombre AS nombre_cliente,
        cl.apellido AS apellido_cliente,
        b.nombre AS barbero,
        s.nombre AS servicio,
        c.fecha_cita,
        c.hora_cita
    FROM citas c
    JOIN clientes cl ON c.id_cliente = cl.id_cliente
    JOIN barberos b ON c.id_barbero = b.id_barbero
    JOIN servicios s ON c.id_servicio = s.id_servicio
    $where
    ORDER BY c.fecha_cita DESC, c.hora_cita DESC
";
$stmt = $db->prepare($sqlCitas);
$stmt->execute($params);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
    <div class="container py-5" style="margin-top:20px;">
        <!-- Título y botón HOME -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=reportes" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> 
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">📅 Reporte de Citas</h1>
        </div>

        <!-- Filtros -->
        <form method="GET" action="index.php" 
            class="row g-3 mb-4 shadow-sm border-0 rounded-4 p-3 align-items-center" 
            style="background: rgba(0,0,0,0.3); border-radius: 15px;">

            <input type="hidden" name="page" value="reportes_citas">

            <!-- Año -->
            <div class="col-md-2 text-center">
                <label class="form-label text-white">Año</label>
                <input type="number" 
                    name="anio" 
                    class="form-control text-center" 
                    value="<?= htmlspecialchars($anio) ?>">
            </div>

            <!-- Mes -->
            <div class="col-md-2 text-center">
                <label class="form-label text-white">Mes</label>
                <select name="mes" class="form-select text-center justify-content-center">
                    <option value="">--</option>
                    <?php 
                    $meses = [
                        1=>"Enero", 2=>"Febrero", 3=>"Marzo", 
                        4=>"Abril", 5=>"Mayo", 6=>"Junio", 
                        7=>"Julio", 8=>"Agosto", 9=>"Septiembre", 
                        10=>"Octubre", 11=>"Noviembre", 12=>"Diciembre"
                    ];
                    foreach ($meses as $num => $nombre): ?>
                        <option value="<?= $num ?>" <?= ((string)$mes === (string)$num) ? 'selected' : '' ?>>
                            <?= $nombre ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Día -->
            <div class="col-md-2 text-center">
                <label class="form-label text-white">Día</label>
                <input type="number" 
                    name="dia" 
                    min="1" 
                    max="31" 
                    class="form-control text-center" 
                    value="<?= htmlspecialchars($dia) ?>">
            </div>

            <!-- Cliente -->
            <div class="col-md-2 text-center">
                <label class="form-label text-white">Cliente</label>
                <input type="text" name="cliente" 
                       class="form-control text-center"
                       placeholder="Nombre o apellido"
                       value="<?= htmlspecialchars($cliente) ?>">
            </div>

            <!-- Barbero -->
            <div class="col-md-2 text-center">
                <label class="form-label text-white">Barbero</label>
                <input type="text" name="barbero" 
                       class="form-control text-center"
                       placeholder="Barbero"
                       value="<?= htmlspecialchars($barbero) ?>">
            </div>

            <!-- Botones -->
            <div class="col-md-2 d-flex align-items-end justify-content-between">
                <button type="submit" class="btn btn-neon w-50 me-2">Filtrar</button>
                <a href="index.php?page=reportes_citas" class="btn btn-secondary w-50">Limpiar</a>
            </div>
        </form>

        <!-- Métricas -->
        <div class="row g-4 mb-4">
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
                📋 Listado de Citas
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bg-dark table-hover align-middle mb-0">
                        <thead class="bg-dark">
                            <tr>
                                <th>Cliente</th>
                                <th>Barbero</th>
                                <th>Servicio</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($citas)): ?>
                                <?php foreach ($citas as $cita): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cita['nombre_cliente'] . " " . $cita['apellido_cliente']) ?></td>
                                        <td><?= htmlspecialchars($cita['barbero']) ?></td>
                                        <td><?= htmlspecialchars($cita['servicio']) ?></td>
                                        <td><?= htmlspecialchars($cita['fecha_cita']) ?></td>
                                        <td><?= htmlspecialchars($cita['hora_cita']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No hay citas para el período seleccionado</td>
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
                <a href="app/views/reportes/generar_pdf.php?anio=<?= urlencode($anio) ?>&mes=<?= urlencode($mes) ?>&dia=<?= urlencode($dia) ?>&cliente=<?= urlencode($cliente) ?>&barbero=<?= urlencode($barbero) ?>" 
                target="_blank" 
                class="btn btn-neon float-end">
                📄 Descargar PDF
                </a>
            </div>
        </div>
    </div>
<main>