<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../libs/dompdf/autoload.inc.php';
require_once __DIR__ . '/../../../config/database.php';
use Dompdf\Dompdf;

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
// Listado de citas con cliente y servicio
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

// ==========================
// Generar HTML PDF
// ==========================
$html = '
<style>
body { font-family: Arial, Helvetica, sans-serif; font-size:12px; }
h2, h3, p { text-align:center; }
table { width:100%; border-collapse:collapse; margin-bottom:15px; }
th, td { padding:0.5rem; border:1px solid #444; text-align:center; }
th { background-color:#0d6efd; color:white; font-weight:700; }
tr:nth-child(even){background:#f2f2f2;} tr:nth-child(odd){background:#fff;}
</style>
<h2>Reporte Barbero - Horas y Citas</h2>
<p>Periodo: ' . ($anio?$anio:'Todos') . ($mes?" - Mes $mes":'') . ($dia?" - Día $dia":'') . '</p>
<h3>Barbero Más Solicitado: '.htmlspecialchars($barberoNombre).' ('.$barberoCitas.' citas)</h3>

<h3>Horas Trabajadas por Barbero</h3>
<table>
<thead><tr><th>Barbero</th><th>Total Citas</th><th>Horas Trabajadas</th></tr></thead>
<tbody>';

if(!empty($horasTrabajadas)){
    foreach($horasTrabajadas as $h){
        $html .= '<tr><td>'.htmlspecialchars($h['barbero']).'</td><td>'.$h['total_citas'].'</td><td>'.$h['total_horas'].' horas</td></tr>';
    }
}else{
    $html .= '<tr><td colspan="3">No hay citas para el período seleccionado</td></tr>';
}

$html .= '</tbody></table>

<h3>Listado de Citas</h3>
<table>
<thead><tr><th>ID</th><th>Barbero</th><th>Cliente</th><th>Servicio</th><th>Fecha</th><th>Hora</th><th>Estado</th></tr></thead>
<tbody>';

if(!empty($citas)){
    foreach($citas as $c){
        $html .= '<tr>
            <td>'.$c['id_cita'].'</td>
            <td>'.htmlspecialchars($c['barbero']).'</td>
            <td>'.htmlspecialchars($c['cliente_nombre'].' '.$c['cliente_apellido']).'</td>
            <td>'.htmlspecialchars($c['servicio']).'</td>
            <td>'.$c['fecha_cita'].'</td>
            <td>'.$c['hora_cita'].'</td>
            <td>'.$c['estado'].'</td>
        </tr>';
    }
}else{
    $html .= '<tr><td colspan="7">No hay citas para el período seleccionado</td></tr>';
}

$html .= '</tbody></table>';

// ==========================
// Generar PDF
// ==========================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Reporte_Horas_Barbero.pdf', ["Attachment" => true]);