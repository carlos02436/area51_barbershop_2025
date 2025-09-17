<?php
require_once __DIR__ . '/../../controllers/CitasController.php';
require_once __DIR__ . '/../../controllers/ServicioController.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';
require_once __DIR__ . '/../../controllers/BarberoController.php';
require_once __DIR__ . '/../../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Instancias
$citasController = new CitasController($db);
$servicioController = new ServicioController();
$clienteController = new ClienteController();
$barberoController = new BarberoController();

// ID cita
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php?page=citas");
    exit();
}

// Obtener cita actual
$cita = $citasController->obtenerCita($id);

// Listas para selects
$clientes = $clienteController->listarClientes();
$barberos = $barberoController->listarBarberos();
$servicios = $servicioController->listarServicios();

// Función para formato de hora AM/PM
function formatearHora($hora): string {
    return date("h:i A", strtotime($hora));
}

// Obtener horas ocupadas del barbero en la fecha de la cita
$horasOcupadas = $citasController->horasOcupadas($cita['id_barbero'], $cita['fecha_cita']);

// Horas disponibles (puedes ajustarlas según tu horario de atención)
$horasPosibles = ['09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'];

// Si se envió formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $citasController->actualizarCita(
        $id,
        $_POST['id_cliente'],
        $_POST['id_barbero'],
        $_POST['id_servicio'],
        $_POST['fecha_cita'],
        $_POST['hora_cita']
    );
    header("Location: index.php?page=citas");
    exit();
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <form method="POST">
                <!-- Cliente -->
                <div class="mb-3">
                    <label for="id_cliente" class="form-label">Cliente</label>
                    <select name="id_cliente" id="id_cliente" class="form-select" required>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id_cliente'] ?>"
                                <?= $cliente['id_cliente'] == $cita['id_cliente'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Barbero -->
                <div class="mb-3">
                    <label for="id_barbero" class="form-label">Barbero</label>
                    <select name="id_barbero" id="id_barbero" class="form-select" required>
                        <?php foreach ($barberos as $barbero): ?>
                            <option value="<?= $barbero['id_barbero'] ?>"
                                <?= $barbero['id_barbero'] == $cita['id_barbero'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($barbero['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Servicio -->
                <div class="mb-3">
                    <label for="id_servicio" class="form-label">Servicio</label>
                    <select class="form-select" id="id_servicio" name="id_servicio" onchange="mostrarImagen()">
                        <?php foreach ($servicios as $servicio): ?>
                            <option value="<?= $servicio['id_servicio'] ?>"
                                data-img="app/uploads/servicios/<?= $servicio['img_servicio'] ?>"
                                <?= ($servicio['id_servicio'] == $cita['id_servicio']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($servicio['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imagen del servicio -->
                <div class="mb-3 text-center">
                    <img id="servicioImagen" 
                        src="app/uploads/servicios/<?= $cita['img_servicio'] ?? 'NoDisp.jpg' ?>" 
                        alt="Imagen servicio" 
                        style="max-width: 200px; border-radius: 8px;">
                </div>

                <!-- Fecha -->
                <div class="mb-3">
                    <label for="fecha_cita" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha_cita" name="fecha_cita" value="<?= $cita['fecha_cita'] ?>" required>
                </div>

                <!-- Hora -->
                <div class="mb-3">
                    <label for="hora_cita" class="form-label">Hora</label>
                    <select name="hora_cita" id="hora_cita" class="form-select" required>
                        <?php
                        foreach ($horasPosibles as $hora) {
                            // Mostrar si no está ocupada o si es la hora actual de la cita
                            if (!in_array($hora, $horasOcupadas) || $hora == $cita['hora_cita']) {
                                $selected = $hora == $cita['hora_cita'] ? 'selected' : '';
                                echo "<option value='$hora' $selected>".formatearHora($hora)."</option>";
                            }
                        }
                        ?>
                    </select>
                    <small class="text-muted">Hora actual: <?= formatearHora($cita['hora_cita']) ?></small>
                </div>

                <!-- Botones -->
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="index.php?page=citas" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>  

    <script>
    // Cambiar imagen de servicio al seleccionar
    function mostrarImagen() {
        const select = document.getElementById("id_servicio");
        const img = document.getElementById("servicioImagen");
        const option = select.options[select.selectedIndex];
        img.src = option.getAttribute("data-img") || "app/uploads/servicios/NoDisp.jpg";
    }
    </script>
<main>