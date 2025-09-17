<?php
require_once __DIR__ . '/../../controllers/CitasController.php';
require_once __DIR__ . '/../../controllers/ServicioController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$citasController = new CitasController($db);
$serviciosController = new ServicioController();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php?page=citas");
    exit;
}

$cita = $citasController->obtenerCita($id);
$servicios = $servicioController->listarServicios();

// Función para pasar hora militar a AM/PM
function formatearHora($hora) {
    return date("h:i A", strtotime($hora));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servicios = $serviciosController->listarServicios();
    header("Location: index.php?page=citas");
    exit;
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Nueva Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <form action="" method="POST" class="text-white">
            <!-- Nombre del Cliente -->
            <div class="mb-3">
                <label for="nombre_cliente" class="form-label">Nombre del Cliente</label>
                <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" 
                    value="<?= htmlspecialchars($cita['nombre_cliente']) ?>" required>
            </div>

            <!-- Teléfono -->
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" 
                    value="<?= htmlspecialchars($cita['telefono']) ?>" required>
            </div>

            <!-- Servicio -->
            <div class="mb-3">
                <label for="id_servicio" class="form-label">Servicio</label>
                <select name="id_servicio" id="id_servicio" class="form-control" required>
                    <?php foreach ($servicios as $servicio): ?>
                        <option value="<?= $servicio['id_servicio'] ?>"
                            data-img="<?= $servicio['img_servicio'] ?>"
                            <?= $cita['id_servicio'] == $servicio['id_servicio'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($servicio['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Imagen Servicio -->
            <div class="mb-3 text-center">
                <img id="imgServicio" 
                    src="app/uploads/servicios/<?= htmlspecialchars($cita['img_servicio'] ?? '') ?>" 
                    style="max-width:150px; max-height:150px;" 
                    alt="Imagen del servicio">
            </div>

            <!-- Fecha -->
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" 
                    value="<?= $cita['fecha'] ?>" required>
            </div>

            <!-- Hora -->
            <div class="mb-3">
                <label for="hora" class="form-label">Hora</label>
                <input type="time" name="hora" id="hora" class="form-control" 
                    value="<?= date('H:i', strtotime($cita['hora'])) ?>" required>
                <small class="text-muted">Hora actual: <?= formatearHora($cita['hora']) ?></small>
            </div>

            <!-- Botón -->
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <!-- JS para cambiar imagen -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const servicioSelect = document.getElementById("id_servicio");
        const imgServicio = document.getElementById("img_servicio");

        servicioSelect.addEventListener("change", function() {
            const selectedOption = servicioSelect.options[servicioSelect.selectedIndex];
            const imgFile = selectedOption.getAttribute("data-img");
            imgServicio.src = "app/uploads/servicios/" + imgFile;
        });
    });
    </script>
<main>