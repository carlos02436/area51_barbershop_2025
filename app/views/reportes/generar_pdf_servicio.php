<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../libs/dompdf/autoload.inc.php';
require_once __DIR__ . '/../../../config/database.php';

use Dompdf\Dompdf;

// ==========================
// Filtros desde GET
// ==========================
$anio = $_GET['anio'] ?? date('Y');
$mes  = $_GET['mes'] ?? '';
$dia  = $_GET['dia'] ?? '';

$condiciones = [];
$params = [];

if (!empty($anio)) { $condiciones[] = "YEAR(c.fecha_cita) = :anio"; $params[':anio'] = $anio; }
if (!empty($mes)) { $condiciones[] = "MONTH(c.fecha_cita) = :mes"; $params[':mes'] = $mes; }
if (!empty($dia)) { $condiciones[] = "DAY(c.fecha_cita) = :dia"; $params[':dia'] = $dia; }

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

// ==========================
// Servicio más solicitado
// ==========================
$sqlServicio = "SELECT s.nombre AS servicio, COUNT(c.id_cita) AS total_citas
                FROM citas c
                INNER JOIN servicios s ON c.id_servicio = s.id_servicio
                $where
                GROUP BY c.id_servicio
                ORDER BY total_citas DESC
                LIMIT 1";
$stmt = $db->prepare($sqlServicio);
$stmt->execute($params);
$servicioSolicitado = $stmt->fetch(PDO::FETCH_ASSOC);
$servicioNombre = $servicioSolicitado['servicio'] ?? 'No hay datos';
$servicioCitas = $servicioSolicitado['total_citas'] ?? 0;

// ==========================
// Listado de citas
// ==========================
$sqlCitas = "SELECT 
                c.id_cita,
                b.nombre AS barbero,
                cl.nombre AS cliente_nombre,
                cl.apellido AS cliente_apellido,
                s.nombre AS servicio,
                c.fecha_cita,
                c.hora_cita,
                c.estado
             FROM citas c
             INNER JOIN barberos b ON c.id_barbero = b.id_barbero
             INNER JOIN clientes cl ON c.id_cliente = cl.id_cliente
             INNER JOIN servicios s ON c.id_servicio = s.id_servicio
             $where
             ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
$stmt = $db->prepare($sqlCitas);
$stmt->execute($params);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================
// Generar HTML PDF
// ==========================
$html = '
<style>
    body { font-family: "Helvetica", Arial, sans-serif; font-size: 12px; color: #000; background-color: #fff; }
    h2 { text-align:center; font-weight: 700; margin-bottom: 10px; }
    p { text-align:center; margin-top: 0; margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    th, td { padding: 0.75rem; vertical-align: middle; border: 1px solid #444; text-align: center; }
    th { background-color: #0d6efd; color: white; font-weight: 700; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    tr:nth-child(odd) { background-color: #ffffff; }
    tr:hover { background-color: #e0e0e0; }
    h3 { margin: 5px 0; text-align:right; }
</style>
<h2>Reporte de Citas y Servicio Más Solicitado</h2>
<p>Periodo: ' . ($anio ? $anio : 'Todos') . ' ' . ($mes ? "- Mes $mes" : '') . ' ' . ($dia ? "- Día $dia" : '') . '</p>

<div style="margin-bottom:15px;">
    <h3>Servicio Más Solicitado: ' . htmlspecialchars($servicioNombre) . '</h3>
    <h3>Total Citas: ' . $servicioCitas . '</h3>
</div>

<table>
    <thead>
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
    <tbody>';

if (!empty($citas)) {
    foreach ($citas as $cita) {
        $html .= '<tr>
            <td>' . $cita['id_cita'] . '</td>
            <td>' . htmlspecialchars($cita['barbero']) . '</td>
            <td>' . htmlspecialchars($cita['cliente_nombre'] . ' ' . $cita['cliente_apellido']) . '</td>
            <td>' . htmlspecialchars($cita['servicio']) . '</td>
            <td>' . $cita['fecha_cita'] . '</td>
            <td>' . $cita['hora_cita'] . '</td>
            <td>' . $cita['estado'] . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="7" style="text-align:center;">No hay citas para el período seleccionado</td></tr>';
}

$html .= '</tbody></table>';

// ==========================
// Generar PDF
// ==========================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Reporte_Servicio.pdf', ["Attachment" => true]);