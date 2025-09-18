<?php
// ==========================
// generar_pdf_dashboard.php
// ==========================

// Iniciar sesión si no hay ninguna activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir Dompdf
require_once __DIR__ . '/../../libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Incluir base de datos y modelos
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../controllers/dashboardController.php';
require_once __DIR__ . '/../../models/Cita.php';

// === Estadísticas ===

// Total clientes
$stmt = $db->query("SELECT COUNT(*) AS total FROM clientes");
$clientesCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Citas de hoy
$stmt = $db->prepare("SELECT COUNT(*) AS total FROM citas WHERE DATE(fecha_cita) = CURDATE()");
$stmt->execute();
$citasCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Barberos
$stmt = $db->query("SELECT COUNT(*) AS total FROM barberos");
$barberosCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Ingresos de hoy
$stmt = $db->prepare("
    SELECT SUM(s.precio) AS total 
    FROM citas c
    INNER JOIN servicios s ON c.id_servicio = s.id_servicio
    WHERE DATE(c.fecha_cita) = CURDATE()
");
$stmt->execute();
$ingresosHoy = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Próximas citas
$stmt = $db->prepare("
    SELECT 
        c.id_cita, 
        cl.nombre AS nombre,
        cl.apellido AS apellido,
        b.nombre AS barbero, 
        c.fecha_cita, 
        c.hora_cita, 
        s.nombre AS servicio
    FROM citas c
    JOIN clientes cl ON c.id_cliente = cl.id_cliente
    JOIN barberos b ON c.id_barbero = b.id_barbero
    JOIN servicios s ON c.id_servicio = s.id_servicio
    WHERE DATE(c.fecha_cita) >= CURDATE()
    ORDER BY c.fecha_cita, c.hora_cita
    LIMIT 10
");
$stmt->execute();
$proximasCitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Servicios más solicitados
$stmt = $db->query("
    SELECT s.nombre, COUNT(c.id_cita) AS cantidad
    FROM citas c
    JOIN servicios s ON c.id_servicio = s.id_servicio
    GROUP BY s.nombre
    ORDER BY cantidad DESC
    LIMIT 5
");
$serviciosPopulares = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Últimos clientes registrados
$stmt = $db->query("
    SELECT nombre, fecha_registro
    FROM clientes
    ORDER BY fecha_registro DESC
    LIMIT 5
");
$ultimosClientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// === Generar HTML para el PDF ===
ob_start();
?>
<h1 style="text-align:center;">Dashboard General</h1>

<h3>Resumen</h3>
<ul>
    <li>Clientes: <?= $clientesCount ?></li>
    <li>Citas de hoy: <?= $citasCount ?></li>
    <li>Barberos: <?= $barberosCount ?></li>
    <li>Ingresos de hoy: $<?= number_format($ingresosHoy, 2, ',', '.') ?></li>
</ul>

<h3>Próximas citas</h3>
<table border="1" cellspacing="0" cellpadding="5">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
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
                <td><?= htmlspecialchars($cita['nombre']) ?></td>
                <td><?= htmlspecialchars($cita['apellido']) ?></td>
                <td><?= htmlspecialchars($cita['barbero']) ?></td>
                <td><?= htmlspecialchars($cita['fecha_cita']) ?></td>
                <td><?= htmlspecialchars($cita['hora_cita']) ?></td>
                <td><?= htmlspecialchars($cita['servicio']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No hay próximas citas</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h3>Servicios más solicitados</h3>
<ul>
    <?php foreach ($serviciosPopulares as $servicio): ?>
        <li><?= htmlspecialchars($servicio['nombre']) ?> - <?= $servicio['cantidad'] ?></li>
    <?php endforeach; ?>
</ul>

<h3>Últimos clientes registrados</h3>
<ul>
    <?php foreach ($ultimosClientes as $cliente): ?>
        <li><?= htmlspecialchars($cliente['nombre']) ?> - <?= htmlspecialchars($cliente['fecha_registro']) ?></li>
    <?php endforeach; ?>
</ul>

<?php
$html = ob_get_clean();

// === Instanciar Dompdf ===
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Configuración de tamaño y orientación
$dompdf->setPaper('A4', 'portrait');

// Renderizar y mostrar PDF
$dompdf->render();
$dompdf->stream("dashboard.pdf", ["Attachment" => true]);
exit;
?>