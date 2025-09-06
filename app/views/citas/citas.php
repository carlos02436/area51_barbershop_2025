<?php
// Asegurarnos de que $citas siempre sea un array
if (!isset($citas) || !is_array($citas)) {
    $citas = [];
}
?>

<body>

        <div class="container py-5" style="margin:100px;">
            <!-- Título y botón HOME -->
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 mb-5 text-center text-md-start">
                <a href="index.php?page=panel" class="btn btn-neon d-flex justify-content-center align-items-center rounded-circle mb-3 mb-md-0"
                style="width: 60px; height: 60px;">
                    <i class="bi bi-house-fill fs-3"></i>
                </a>
                <h1 class="fw-bold display-5 text-white mb-0">📊 Citas</h1>
        </div>
        <!-- Tabla de citas -->
        <table class="table table-striped table-bordered rounded shadow-sm mx-auto" style="max-width: 2000px;">
            <thead class="table-dark">
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
                <?php if (!empty($citas)): ?>
                    <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><?= htmlspecialchars($cita['id_cita']) ?></td>
                            <td><?= htmlspecialchars($cita['nombre_cliente']) ?></td>
                            <td><?= htmlspecialchars($cita['nombre_barbero']) ?></td>
                            <td><?= htmlspecialchars($cita['nombre_servicio']) ?></td>
                            <td><?= htmlspecialchars($cita['fecha_cita']) ?></td>
                            <td><?= htmlspecialchars($cita['hora_cita']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($cita['estado'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay citas disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <main>