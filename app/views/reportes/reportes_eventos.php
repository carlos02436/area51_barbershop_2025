<?php
require_once __DIR__ . '/../auth_admin.php';

// Verifica si el administrador está logueado
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../../../config/database.php';

// ==========================
// Filtros
// ==========================
$anio = $_GET['anio'] ?? '';
$mes  = $_GET['mes'] ?? '';
$dia  = $_GET['dia'] ?? '';

$condiciones = [];
$params = [];

if (!empty($anio)) {
    $condiciones[] = "YEAR(fecha_publicacion) = :anio";
    $params[':anio'] = $anio;
}
if (!empty($mes)) {
    $condiciones[] = "MONTH(fecha_publicacion) = :mes";
    $params[':mes'] = $mes;
}
if (!empty($dia)) {
    $condiciones[] = "DAY(fecha_publicacion) = :dia";
    $params[':dia'] = $dia;
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

// ==========================
// Listado de eventos
// ==========================
$sqlEventos = "SELECT id_noticia, titulo, contenido, fecha_publicacion, publicado_por
               FROM noticias
               $where
               ORDER BY fecha_publicacion DESC";
$stmt = $db->prepare($sqlEventos);
$stmt->execute($params);
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
    <div class="container py-5" style="margin-top:20px;">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start" style="margin-top:100px;">
            <a href="index.php?page=reportes" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0" style="width: 60px; height: 60px;"> 
                <i class="bi bi-house-fill fs-3"></i>
            </a>
            <h1 class="fw-bold display-5 text-white mb-0">📅 Eventos Realizados</h1>
        </div>

        <!-- Filtros -->
        <form method="GET" action="index.php" class="d-flex justify-content-center align-items-center gap-3 mb-4 shadow-sm border-0 rounded-4 p-3" style="background: rgba(0,0,0,0.3); flex-wrap: wrap;">
            <input type="hidden" name="page" value="reportes_eventos">
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

            <div class="d-flex flex-column align-items-center">
                <label class="form-label text-white mb-1">Día</label>
                <input type="number" name="dia" min="1" max="31" class="form-control text-center" style="width: <?= $ancho ?>;" value="<?= htmlspecialchars($dia) ?>">
            </div>

            <div class="d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-neon" style="width: <?= $ancho ?>;">Filtrar</button>
                <a href="index.php?page=reportes_eventos" class="btn btn-secondary" style="width: <?= $ancho ?>;">Limpiar</a>
            </div>
        </form>

        <!-- Tabla de eventos -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-dark text-white fw-bold text-center">
                📋 Listado de Eventos
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bg-dark table-hover align-middle mb-0">
                        <thead class="bg-dark">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Contenido</th>
                                <th>Fecha Publicación</th>
                                <th>Publicado Por</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($eventos)): ?>
                                <?php foreach ($eventos as $evento): ?>
                                    <tr>
                                        <td><?= $evento['id_noticia'] ?></td>
                                        <td><?= htmlspecialchars($evento['titulo']) ?></td>
                                        <td><?= htmlspecialchars($evento['contenido']) ?></td>
                                        <td><?= $evento['fecha_publicacion'] ?></td>
                                        <td><?= !empty($evento['publicado_por']) ? $evento['publicado_por'] : 'Desconocido' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No hay eventos para el período seleccionado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Botón PDF -->
        <div class="d-flex justify-content-end">
            <a href="app/views/reportes/generar_pdf_eventos.php?anio=<?= urlencode($anio) ?>&mes=<?= urlencode($mes) ?>&dia=<?= urlencode($dia) ?>" 
            target="_blank" 
            class="btn btn-neon">
            📄 Descargar PDF
            </a>
        </div>
    </div>
<main>