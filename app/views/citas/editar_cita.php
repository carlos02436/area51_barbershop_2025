<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/CitasController.php';

$controller = new CitasController($db);

// ------------------------------
// Función para generar horas disponibles según barbero y fecha
// ------------------------------
function generarHorasDisponibles($id_barbero, $fecha, $ocupadas) {
    $dayOfWeek = date('w', strtotime($fecha));
    $horas = [];

    if ($id_barbero == 1) { // Yeison Sarmiento
        if ($dayOfWeek == 0) return []; // No trabaja domingos
        for ($h = 8; $h < 20; $h++) {
            if ($h >= 12 && $h < 14) continue; // Descanso 12-14
            $hora = str_pad($h,2,'0',STR_PAD_LEFT).":00:00";
            if (!in_array($hora, $ocupadas)) $horas[] = $hora;
        }
    } else {
        $inicio = 8;
        $fin = 20;
        for ($h = $inicio; $h < $fin; $h++) {
            $hora = str_pad($h,2,'0',STR_PAD_LEFT).":00:00";
            if (!in_array($hora, $ocupadas)) $horas[] = $hora;
        }
    }

    return $horas;
}

// ------------------------------
// Obtener la cita
// ------------------------------
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php?page=citas');
    exit();
}

$cita = $controller->obtenerCita($id);

// Traer clientes, barberos y servicios
$clientes = $db->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$barberos = $db->query("SELECT * FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $db->query("SELECT * FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

// Manejo del POST para actualizar la cita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $id_barbero = $_POST['id_barbero'];
    $id_servicio = $_POST['id_servicio'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];

    $ok = $controller->actualizarCita($id, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);
    if ($ok) header('Location: index.php?page=citas');
    else $error = "No se pudo actualizar la cita. Verifica la hora disponible.";
}

// Calcular horas disponibles
$horasDisponibles = [];
$barberoSeleccionado = $_POST['id_barbero'] ?? $cita['id_barbero'];
$fechaSeleccionada = $_POST['fecha_cita'] ?? $cita['fecha_cita'];

if ($barberoSeleccionado && $fechaSeleccionada) {
    $ocupadas = $controller->horasOcupadas($barberoSeleccionado, $fechaSeleccionada);
    // Excluir la hora actual de la cita para que siga disponible
    $ocupadas = $citas($ocupadas, fn($h) => $h !== $cita['hora_cita']);
    $horasDisponibles = generarHorasDisponibles($barberoSeleccionado, $fechaSeleccionada, $ocupadas);
}

// Obtener imagen del servicio seleccionado
$imgServicio = '';
if (isset($_POST['id_servicio']) && $_POST['id_servicio'] != '') {
    $sIndex = array_search($_POST['id_servicio'], array_column($servicios, 'id_servicio'));
    if ($sIndex !== false) $imgServicio = $servicios[$sIndex]['img_servicio'];
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
            <form action="" method="POST" class="text-white">

                <!-- Cliente -->
                <div class="mb-3">
                    <label>Cliente</label>
                    <select name="id_cliente" class="form-control w-100" required>
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['id_cliente'] ?>" <?= ($c['id_cliente'] == $cita['id_cliente']) ? 'selected' : '' ?>>
                                <?= $c['nombre'].' '.$c['apellido'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Barbero -->
                <div class="mb-3">
                    <label>Barbero</label>
                    <select name="id_barbero" class="form-control w-100" required onchange="this.form.submit()">
                        <?php foreach($barberos as $b): ?>
                            <option value="<?= $b['id_barbero'] ?>" <?= ($b['id_barbero'] == $cita['id_barbero']) ? 'selected' : '' ?>>
                                <?= $b['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Servicio -->
                <div class="mb-3">
                    <label>Servicio</label>
                    <select name="id_servicio" class="form-control w-100" id="servicio" required onchange="mostrarImagen()">
                        <?php foreach($servicios as $s): ?>
                            <option value="<?= $s['id_servicio'] ?>" data-img="<?= $s['img_servicio'] ?>" <?= ($s['id_servicio'] == $cita['id_servicio']) ? 'selected' : '' ?>>
                                <?= $s['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imagen -->
                <div class="mb-3 text-center" id="imagenContainer" style="display: none;">
                    <img id="imgServicio" src="" style="max-width:150px; max-height:200px; border: 3px solid #00ff00; border-radius: 8px;">
                </div>

                <!-- Fecha -->
                <div class="mb-3">
                    <label>Fecha</label>
                    <input type="date" name="fecha_cita" class="form-control w-100" value="<?= $fechaSeleccionada ?>" required onchange="this.form.submit()">
                </div>

                <!-- Hora -->
                <div class="mb-3">
                    <label>Hora</label>
                    <select name="hora_cita" class="form-control w-100" required>
                        <!-- Hora actual -->
                        <option value="<?= $cita['hora_cita'] ?>" selected>
                            <?= date('h:i A', strtotime($cita['hora_cita'])) ?>
                        </option>
                        <!-- Horas disponibles -->
                        <?php foreach($horasDisponibles as $h): ?>
                            <option value="<?= $h ?>"><?= date('h:i A', strtotime($h)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=citas" class="btn btn-danger w-25">Cancelar</a>
                    <button type="submit" class="btn btn-neon w-25">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function mostrarImagen(){
            const sel = document.getElementById('servicio');
            const img = sel.selectedOptions[0].dataset.img;
            const imgContainer = document.getElementById('imagenContainer');
            const imgElement = document.getElementById('imgServicio');
            
            if (sel.value !== '' && img) {
                imgElement.src = 'app/uploads/servicios/' + img;
                imgContainer.style.display = 'block';
            } else {
                imgContainer.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', mostrarImagen);
    </script>
<main>