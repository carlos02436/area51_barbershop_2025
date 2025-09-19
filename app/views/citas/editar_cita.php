<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/CitasController.php';

$controller = new CitasController($db);

// Obtener la cita
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
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
            <form action="" method="POST" class="text-white">

                <!-- Cliente -->
                <div class="mb-3">
                    <label>Cliente</label>
                    <select name="id_cliente" class="form-control" required>
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
                    <select name="id_barbero" class="form-control" required>
                        <?php foreach($barberos as $b): ?>
                            <option value="<?= $b['id_barbero'] ?>" <?= $b['id_barbero'] == $cita['id_barbero'] ? 'selected' : '' ?>>
                                <?= $b['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Servicio -->
                <div class="mb-3">
                    <label>Servicio</label>
                    <select name="id_servicio" class="form-control" id="servicio" required onchange="mostrarImagen()">
                        <?php foreach($servicios as $s): ?>
                            <option value="<?= $s['id_servicio'] ?>" data-img="<?= $s['img_servicio'] ?>" <?= $s['id_servicio'] == $cita['id_servicio'] ? 'selected' : '' ?>>
                                <?= $s['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imagen (oculta inicialmente) -->
                <div class="mb-3 text-center" id="imagenContainer" style="display: none;">
                    <img id="imgServicio" src="" style="max-width:150px; max-height:200px; border: 3px solid #00ff00; border-radius: 8px;">
                </div>

                <!-- Fecha -->
                <div class="mb-3">
                    <label>Fecha</label>
                    <input type="date" name="fecha_cita" class="form-control" value="<?= $cita['fecha_cita'] ?>" required>
                </div>

                <!-- Hora -->
                <div class="mb-3">
                    <label>Hora</label>
                    <select name="hora_cita" class="form-control" required>
                        <!-- Mostrar la hora actual de la cita como seleccionada -->
                        <option value="<?= $cita['hora_cita'] ?>" selected>
                            <?= date('h:i A', strtotime($cita['hora_cita'])) ?>
                        </option>
                        
                        <!-- Mostrar las horas disponibles -->
                        <?php if(!empty($horasDisponibles)): ?>
                            <?php foreach($horasDisponibles as $h): ?>
                                <?php if ($h != $cita['hora_cita']): ?>
                                    <option value="<?= $h ?>">
                                        <?= date('h:i A', strtotime($h)) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=citas" class="btn btn-danger" style="width:100px;">Cancelar</a>
                    <button type="submit" class="btn btn-neon" style="width:100px;">Actualizar</button>
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