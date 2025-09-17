<?php
require_once __DIR__ . '/../../controllers/CitasController.php';

$controller = new CitasController($db);

// Traer clientes, barberos y servicios
$clientes = $db->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$barberos = $db->query("SELECT * FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $db->query("SELECT * FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$horasDisponibles = []; // horas filtradas

// Función para generar horas posibles según barbero y día
function generarHoras($id_barbero, $fecha, $ocupadas) {
    $dayOfWeek = date('w', strtotime($fecha));
    $horas = [];

    if ($id_barbero == 1) { // Yeison Sarmiento
        if ($dayOfWeek == 0) return []; // domingo no trabaja
        $inicio = 8; $fin = 20;
        for ($h=$inicio; $h<$fin; $h++) {
            if ($h >= 11 && $h < 14) continue; // bloque lunch
            $hora = str_pad($h,2,'0',STR_PAD_LEFT).":00:00";
            if (!in_array($hora, $ocupadas)) $horas[] = $hora;
        }
    } else {
        if ($dayOfWeek != 0) { // lunes a viernes 08-20
            $inicio = 8; $fin = 20;
        } else { // domingo 08-16
            $inicio = 8; $fin = 16;
        }
        for ($h=$inicio; $h<$fin; $h++) {
            $hora = str_pad($h,2,'0',STR_PAD_LEFT).":00:00";
            if (!in_array($hora, $ocupadas)) $horas[] = $hora;
        }
    }
    return $horas;
}

// Cuando envían el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $id_barbero = $_POST['id_barbero'];
    $id_servicio = $_POST['id_servicio'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];

    $ok = $controller->crearCita($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);
    if ($ok) {
        header('Location: index.php?page=citas');
        exit();
    } else {
        $error = "Hora no disponible o fuera del horario permitido";
    }
}

// Calcular horas disponibles si se seleccionó barbero y fecha
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
                <div class="mb-3">
                    <label>Cliente</label>
                    <select name="id_cliente" class="form-control" required>
                        <?php foreach($clientes as $c) echo "<option value='{$c['id_cliente']}'>{$c['nombre']}</option>"; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Barbero</label>
                    <select name="id_barbero" class="form-control" id="barbero" required onchange="this.form.submit()">
                        <option value="">Selecciona...</option>
                        <?php foreach($barberos as $b) 
                            $sel = (isset($_POST['id_barbero']) && $_POST['id_barbero']==$b['id_barbero'])?'selected':''; 
                            echo "<option value='{$b['id_barbero']}' $sel>{$b['nombre']}</option>"; 
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Fecha</label>
                    <input type="date" name="fecha_cita" class="form-control" id="fecha" value="<?= $_POST['fecha_cita'] ?? '' ?>" required onchange="this.form.submit()">
                </div>

                <div class="mb-3">
                    <label>Hora</label>
                    <select name="hora_cita" class="form-control" required>
                        <?php foreach($horasDisponibles as $h) echo "<option value='$h'>$h</option>"; ?>
                        <?php if(empty($horasDisponibles)) echo "<option value=''>--Selecciona barbero y fecha--</option>"; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Servicio</label>
                    <select name="id_servicio" class="form-control" id="servicio" required onchange="mostrarImagen()">
                        <?php foreach($servicios as $s) 
                            $sel = (isset($_POST['id_servicio']) && $_POST['id_servicio']==$s['id_servicio'])?'selected':''; 
                            echo "<option value='{$s['id_servicio']}' data-img='{$s['img_servicio']}' $sel>{$s['nombre']}</option>"; 
                        ?>
                    </select>
                </div>

                <div class="mb-3 text-center">
                    <img id="imgServicio" src="<?= isset($_POST['id_servicio']) ? $servicios[array_search($_POST['id_servicio'], array_column($servicios,'id_servicio'))]['img_servicio'] : '' ?>" style="max-width:150px; max-height:150px;">
                </div>

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
        document.getElementById('imgServicio').src = img ? img : '';
    }
    </script>
<main>