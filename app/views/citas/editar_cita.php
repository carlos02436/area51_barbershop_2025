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
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
            <form action="" method="POST" class="text-white">
                <div class="mb-3">
                    <label>Cliente</label>
                    <select name="id_cliente" class="form-control" required>
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['id_cliente'] ?>" <?= $c['id_cliente'] == $cita['id_cliente'] ? 'selected' : '' ?>>
                                <?= $c['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

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

                <div class="mb-3 text-center">
                    <img id="imgServicio" src="<?= $cita['img_servicio'] ?>" style="max-width:150px; max-height:150px;">
                </div>

                <div class="mb-3">
                    <label>Fecha</label>
                    <input type="date" name="fecha_cita" class="form-control" value="<?= $cita['fecha_cita'] ?>" required>
                </div>

                <div class="mb-3">
                    <label>Hora</label>
                    <input type="time" name="hora_cita" class="form-control" value="<?= $cita['hora_cita'] ?>" required>
                </div>

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
        document.getElementById('imgServicio').src = img ? img : '';
    }
    </script>
<main>