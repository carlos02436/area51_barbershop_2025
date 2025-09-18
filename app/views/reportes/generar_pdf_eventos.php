<?php
require_once __DIR__ . '/../auth_admin.php';

// Cargar Dompdf
require_once __DIR__ . '/../../libs/dompdf/autoload.inc.php';
require_once __DIR__ . '/../../../config/database.php';
use Dompdf\Dompdf;

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
// Consulta eventos
// ==========================
$sqlEventos = "SELECT id_noticia, titulo, contenido, fecha_publicacion, publicado_por
               FROM noticias
               $where
               ORDER BY fecha_publicacion DESC";
$stmt = $db->prepare($sqlEventos);
$stmt->execute($params);
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================
// Generar HTML
// ==========================
$html = '
<style>
    body { font-family: "Helvetica", Arial, sans-serif; font-size: 12px; color: #000; }
    h2 { text-align:center; font-weight:700; margin-bottom:10px; }
    table { width:100%; border-collapse:collapse; margin-bottom:15px; }
    th, td { padding:0.75rem; vertical-align:middle; border:1px solid #444; text-align:center; }
    th { background-color:#0d6efd; color:white; font-weight:700; }
    tr:nth-child(even) { background-color:#f2f2f2; }
    tr:nth-child(odd) { background-color:#ffffff; }
    tr:hover { background-color:#e0e0e0; }
</style>
<h2>Reporte de Eventos Realizados</h2>
<p>Periodo: ' . ($anio ?: 'Todos') . ' ' . ($mes ? "- Mes $mes" : '') . ' ' . ($dia ? "- Día $dia" : '') . '</p>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Contenido</th>
            <th>Fecha Publicación</th>
            <th>Publicado Por</th>
        </tr>
    </thead>
    <tbody>';

if (!empty($eventos)) {
    foreach ($eventos as $evento) {
        $html .= '<tr>
            <td>' . $evento['id_noticia'] . '</td>
            <td>' . htmlspecialchars($evento['titulo']) . '</td>
            <td>' . htmlspecialchars($evento['contenido']) . '</td>
            <td>' . $evento['fecha_publicacion'] . '</td>
            <td>' . (!empty($evento['publicado_por']) ? $evento['publicado_por'] : 'Desconocido') . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="5" style="text-align:center;">No hay eventos para el período seleccionado</td></tr>';
}

$html .= '</tbody></table>';

// ==========================
// Generar PDF
// ==========================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Reporte_Eventos.pdf', ["Attachment" => true]);