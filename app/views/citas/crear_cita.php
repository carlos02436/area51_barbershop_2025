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

// Función para generar horas disponibles
function generarHoras($id_barbero, $fecha, $ocupadas) {
    $dayOfWeek = date('w', strtotime($fecha));
    $horas = [];

    if ($id_barbero == 1) { // Barbero especial
        if ($dayOfWeek == 0) return [];
        for ($h = 8; $h < 20; $h++) {
            if ($h >= 12 && $h < 14) continue; // Descanso
            $hora = str_pad($h,2,'0',STR_PAD_LEFT).":00:00";
            if (!in_array($hora, $ocupadas)) $horas[] = $hora;
        }
    } else { // Otros barberos
        for ($h = 8; $h < 20; $h++) {
            $hora = str_pad($h,2,'0',STR_PAD_LEFT).":00:00";
            if (!in_array($hora, $ocupadas)) $horas[] = $hora;
        }
    }

    return $horas;
}

// Procesar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_barbero = $_POST['id_barbero'];
    $id_servicio = $_POST['id_servicio'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];

    if(!empty($id_cliente) && !empty($id_barbero) && !empty($id_servicio) && !empty($fecha_cita) && !empty($hora_cita)) {
        $ok = $controller->crearCita($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);

        if ($ok) {
            // Preparar datos
            $servicio = $servicios[array_search($id_servicio, array_column($servicios, 'id_servicio'))];
            $cliente = $clientes[array_search($id_cliente, array_column($clientes, 'id_cliente'))];
            $barbero = $barberos[array_search($id_barbero, array_column($barberos, 'id_barbero'))];

            $precio = $servicio['precio'];
            $nombre_servicio = $servicio['nombre'];

            $hora_cita_formato = date('h:i A', strtotime($hora_cita));
            $fecha_cita_formato = date('d/m/Y', strtotime($fecha_cita));

            // Mensajes estilizados
            $mensaje_cliente = "✅ *CITA CONFIRMADA - AREA51 BARBER SHOP* ✅\n\n".
                               "👤 *Cliente:* {$cliente['nombre']} {$cliente['apellido']}\n".
                               "💈 *Barbero:* {$barbero['nombre']}\n".
                               "✂️ *Servicio:* {$nombre_servicio} - \${$precio}\n".
                               "📅 *Fecha:* {$fecha_cita_formato}\n".
                               "⏰ *Hora:* {$hora_cita_formato}\n\n".
                               "¡Gracias por confiar en nosotros! 💈✨";

            $mensaje_barbero = "📋 *NUEVA CITA AGENDADA* 📋\n\n".
                               "👤 *Cliente:* {$cliente['nombre']} {$cliente['apellido']}\n".
                               "💈 *Barbero:* {$barbero['nombre']}\n".
                               "✂️ *Servicio:* {$nombre_servicio} - \${$precio}\n".
                               "📅 *Fecha:* {$fecha_cita_formato}\n".
                               "⏰ *Hora:* {$hora_cita_formato}\n\n".
                               "¡Prepárate para un excelente servicio! ✂️";

            // Enviar mensajes en segundo plano sin bloquear la respuesta
            $data = [
                'cliente' => ['telefono'=>$cliente['telefono'], 'mensaje'=>$mensaje_cliente],
                'barbero' => ['telefono'=>$barbero['telefono'], 'mensaje'=>$mensaje_barbero]
            ];

            $cmd = 'curl -X POST -H "Content-Type: application/json" -d \'' . json_encode($data) . '\' http://localhost:3000/send-message > NUL 2>&1 &';
            exec($cmd);

            // Redirigir inmediatamente
            header('Location: /area51_barbershop_2025/index.php?page=crear_cita');
            exit();
        } else {
            $error = "Hora no disponible o fuera del horario permitido";
        }
    }
}

// Calcular horas disponibles si se cambian id_barbero o fecha
if (isset($_POST['id_barbero'], $_POST['fecha_cita'])) {
    $ocupadas = $controller->horasOcupadas($_POST['id_barbero'], $_POST['fecha_cita']);
    $horasDisponibles = generarHoras($_POST['id_barbero'], $_POST['fecha_cita'], $ocupadas);
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
                                data-telefono="<?= $c['telefono'] ?>"
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
                                data-telefono="<?= $b['telefono'] ?>"
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
                    <select name="id_servicio" class="form-control" required onchange="mostrarImagen()">
                        <option value="">Selecciona un servicio...</option>
                        <?php foreach($servicios as $s): ?>
                            <option value="<?= $s['id_servicio'] ?>" 
                                    data-img="<?= $s['img_servicio'] ?>" 
                                    data-precio="<?= $s['precio'] ?>"
                                    <?= (isset($_POST['id_servicio']) && $_POST['id_servicio']==$s['id_servicio'])?'selected':'' ?>>
                                <?= $s['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imagen del servicio -->
                <div class="mb-3 text-center" id="imagenContainer" style="display: none;">
                    <img id="imgServicio" src="" style="max-width:150px; max-height:200px; border: 3px solid #00ff00; border-radius: 8px;">
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=home" class="btn btn-danger" style="width:100px;">Cancelar</a>
                    <button type="submit" class="btn btn-neon" style="width:100px;">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function mostrarImagen(){
        const sel = document.querySelector('select[name="id_servicio"]');
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

    // Mostrar imagen al cargar la página si ya hay servicio seleccionado
    document.addEventListener('DOMContentLoaded', function() {
        mostrarImagen();
    });
    </script>
<main>