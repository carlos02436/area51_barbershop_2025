<?php

// Iniciar sesión si no hay ninguna activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cargar Dompdf manual
require_once __DIR__ . '/../../libs/dompdf/autoload.inc.php';

// Cargar base de datos
require_once __DIR__ . '/../../../config/database.php';

use Dompdf\Dompdf;

// Filtros desde GET
$anio = $_GET['anio'] ?? date('Y');
$mes  = $_GET['mes'] ?? '';
$dia  = $_GET['dia'] ?? '';

// Construir condiciones
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

// Consulta ingresos
$sql = "SELECT id_ingreso, periodo, fecha_inicio, fecha_fin, total_ingresos, total_citas, creado_en
        FROM ingresos
        $where
        ORDER BY fecha_inicio DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$ingresos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular totales
$totalIngresos = array_sum(array_column($ingresos, 'total_ingresos'));
$totalCitas    = array_sum(array_column($ingresos, 'total_citas'));

// Generar HTML para PDF
$html = '
<style>
    body { font-family: "Helvetica", Arial, sans-serif; font-size: 12px; color: #000; background-color: #fff; }
    h2 { text-align:center; font-weight: 700; margin-bottom: 10px; color: #000; }
    p { text-align:center; margin-top: 0; margin-bottom: 15px; color: #000; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    th, td { padding: 0.75rem; vertical-align: middle; border: 1px solid #444; text-align: center; }
    th { background-color: #0d6efd; color: white; font-weight: 700; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    tr:nth-child(odd) { background-color: #ffffff; }
    tr:hover { background-color: #e0e0e0; }
    h3 { margin: 5px 0; text-align:right; color: #000; }
</style>
<h2>Reporte de Ingresos</h2>
<p>Periodo: ' . ($anio ? $anio : 'Todos') . ' ' . ($mes ? "- Mes $mes" : '') . ' ' . ($dia ? "- Día $dia" : '') . '</p>
<table>
    <thead>
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
    <tbody>';

if (!empty($ingresos)) {
    foreach ($ingresos as $i) {
        $html .= '<tr>
            <td>' . $i['id_ingreso'] . '</td>
            <td>' . $i['periodo'] . '</td>
            <td>' . $i['fecha_inicio'] . '</td>
            <td>' . $i['fecha_fin'] . '</td>
            <td>' . number_format($i['total_ingresos'], 0, ',', '.') . '</td>
            <td>' . $i['total_citas'] . '</td>
            <td>' . $i['creado_en'] . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="7" style="text-align:center;">No hay ingresos para el periodo seleccionado</td></tr>';
}

$html .= '</tbody></table>
<h3>Total Ingresos: $ ' . number_format($totalIngresos, 0, ',', '.') . '</h3>
<h3>Total Citas: ' . number_format($totalCitas, 0, ',', '.') . '</h3>';

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Mostrar PDF en el navegador sin descargar automáticamente
$dompdf->stream('Reporte_Ingresos.pdf', ["Attachment" => true]);