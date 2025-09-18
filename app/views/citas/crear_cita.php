<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/CitasController.php';

$controller = new CitasController($db);

// Traer clientes, barberos y servicios
$clientes = $db->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$barberos = $db->query("SELECT * FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $db->query("SELECT * FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$horasDisponibles = [];

// Generar horas según barbero y fecha
function generarHoras($id_barbero, $fecha, $ocupadas) {
    $dayOfWeek = date('w', strtotime($fecha)); // 0=Domingo, 1=Lunes,...
    $horas = [];

    if ($id_barbero == 1) { // Yeison Sarmiento
        if ($dayOfWeek == 0) return []; // No trabaja domingos
        for ($h = 8; $h < 20; $h++) {
            if ($h >= 12 && $h < 14) continue; // Descanso 12-14
            $hora = str_pad($h,2,'0',STR_PAD_LEFT).":00:00";
            if (!in_array($hora, $ocupadas)) $horas[] = $hora;
        }
    } else {
        $inicio = ($dayOfWeek == 0) ? 8 : 8;
        $fin = ($dayOfWeek == 0) ? 16 : 20; // Domingo 8-16, otros 8-20
        for ($h = $inicio; $h < $fin; $h++) {
            $hora = str_pad($h,2,'0',STR_PAD_LEFT).":00:00";
            if (!in_array($hora, $ocupadas)) $horas[] = $hora;
        }
    }

    return $horas;
}

// POST: crear cita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_barbero = $_POST['id_barbero'];
    $id_servicio = $_POST['id_servicio'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];

    if(!empty($id_cliente) && !empty($id_barbero) && !empty($id_servicio) && !empty($fecha_cita) && !empty($hora_cita)){
        $ok = $controller->crearCita($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);
        if ($ok) {
            header('Location: index.php?page=citas');
            exit();
        } else {
            $error = "Hora no disponible o fuera del horario permitido";
        }
    }
}

// Calcular horas disponibles si seleccionaron barbero y fecha
if (isset($_POST['id_barbero'], $_POST['fecha_cita'])) {
    $ocupadas = $controller->horasOcupadas($_POST['id_barbero'], $_POST['fecha_cita']);
    $horasDisponibles = generarHoras($_POST['id_barbero'], $_POST['fecha_cita'], $ocupadas);
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
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <?php if($error) echo "<p class='text-danger'>$error</p>"; ?>
            <form action="" method="POST" class="text-white">
                <!-- Cliente -->
                <div class="mb-3">
                    <label>Cliente</label>
                    <select name="id_cliente" class="form-control" required>
                        <option value="">Selecciona un cliente...</option>
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['id_cliente'] ?>" 
                                <?= (isset($_POST['id_cliente']) && $_POST['id_cliente']==$c['id_cliente'])?'selected':'' ?>>
                                <?= $c['nombre'].' '.$c['apellido'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Barbero -->
                <div class="mb-3">
                    <label>Barbero</label>
                    <select name="id_barbero" class="form-control" required onchange="this.form.submit()">
                        <option value="">Selecciona...</option>
                        <?php foreach($barberos as $b): ?>
                            <option value="<?= $b['id_barbero'] ?>" 
                                <?= (isset($_POST['id_barbero']) && $_POST['id_barbero']==$b['id_barbero'])?'selected':'' ?>>
                                <?= $b['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div class="mb-3">
                    <label>Fecha</label>
                    <input type="date" name="fecha_cita" class="form-control" 
                        value="<?= $_POST['fecha_cita'] ?? '' ?>" required onchange="this.form.submit()">
                </div>

                <!-- Hora -->
                <div class="mb-3">
                    <label>Hora</label>
                    <select name="hora_cita" class="form-control" required>
                        <?php if(!empty($horasDisponibles)): ?>
                            <?php foreach($horasDisponibles as $h): ?>
                                <option value="<?= $h ?>" <?= (isset($_POST['hora_cita']) && $_POST['hora_cita']==$h)?'selected':'' ?>>
                                    <?= $h ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">--Selecciona barbero y fecha--</option>
                        <?php endif; ?>
                    </select>
                </div>
                <!-- Servicio -->
                <div class="mb-3">
                    <label>Servicio</label>
                    <select name="id_servicio" class="form-control" id="servicio" required onchange="mostrarImagen()">
                        <option value="">Selecciona un servicio...</option>
                        <?php foreach($servicios as $s): ?>
                            <option value="<?= $s['id_servicio'] ?>" data-img="<?= $s['img_servicio'] ?>" 
                                <?= (isset($_POST['id_servicio']) && $_POST['id_servicio']==$s['id_servicio'])?'selected':'' ?>>
                                <?= $s['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imagen (oculta inicialmente) -->
                <div class="mb-3 text-center" id="imagenContainer" style="display: none;">
                    <img id="imgServicio" src="" style="max-width:150px; max-height:200px; border: 3px solid #00ff00; border-radius: 8px;">
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=citas" class="btn btn-danger" style="width:100px;">Cancelar</a>
                    <button type="submit" class="btn btn-neon" style="width:100px;">Guardar</button>
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
                // Construir la ruta completa de la imagen
                imgElement.src = 'app/uploads/servicios/' + img;
                imgContainer.style.display = 'block';
            } else {
                imgContainer.style.display = 'none';
            }
        }

        // Mostrar imagen si ya hay un servicio seleccionado al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            mostrarImagen();
        });
    </script>
<main>