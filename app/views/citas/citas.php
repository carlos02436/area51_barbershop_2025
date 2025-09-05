<?php
// $citas se pasa desde el controlador
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Barbero</th>
            <th>Servicio</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($citas as $cita): ?>
        <tr>
            <td><?= $cita['id_cita'] ?></td>
            <td><?= $cita['nombre_cliente'] ?></td>
            <td><?= $cita['nombre_barbero'] ?></td>
            <td><?= $cita['nombre_servicio'] ?></td>
            <td><?= $cita['fecha_cita'] ?></td>
            <td><?= $cita['hora_cita'] ?></td>
            <td><?= ucfirst($cita['estado']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>