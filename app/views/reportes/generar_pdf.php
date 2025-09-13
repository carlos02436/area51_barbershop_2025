<?php
require_once __DIR__ . '/../../libs/dompdf/autoload.inc.php';
require_once __DIR__ . '/../../../config/database.php';

use Dompdf\Dompdf;

// 1. Recibir parámetros
$anio    = $_GET['anio']    ?? null;
$mes     = $_GET['mes']     ?? null;
$dia     = $_GET['dia']     ?? null;
$cliente = $_GET['cliente'] ?? null;
$barbero = $_GET['barbero'] ?? null;

// 2. Construir filtros dinámicos
$condiciones = [];
$params = [];

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
if (!empty($cliente)) {
    $condiciones[] = "(cl.nombre LIKE :cliente OR cl.apellido LIKE :cliente)";
    $params[':cliente'] = "%$cliente%";
}
if (!empty($barbero)) {
    $condiciones[] = "b.nombre LIKE :barbero";
    $params[':barbero'] = "%$barbero%";
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

// 3. Consulta
$sql = "
    SELECT 
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
$stmt = $db->prepare($sql);
$stmt->execute($params);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Generar HTML para el PDF
$html = '<h1 style="text-align:center;">Reporte de Citas</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Barbero</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>';

if ($citas) {
    foreach ($citas as $cita) {
        $html .= "<tr>
                    <td>{$cita['nombre_cliente']} {$cita['apellido_cliente']}</td>
                    <td>{$cita['barbero']}</td>
                    <td>{$cita['servicio']}</td>
                    <td>{$cita['fecha_cita']}</td>
                    <td>{$cita['hora_cita']}</td>
                  </tr>";
    }
} else {
    $html .= '<tr><td colspan="5" align="center">No hay citas para este filtro</td></tr>';
}

$html .= '</tbody></table>';

// 5. Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_citas.pdf", ["Attachment" => true]);
exit;