<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/CitasController.php';
require_once __DIR__ . '/../../../config/database.php';

$controller = new CitasController($db);

// Obtener el ID de la cita a editar
$id_cita = $_GET['id'] ?? null;
if (!$id_cita) {
    header('Location: index.php?page=citas');
    exit();
}

// Obtener datos de la cita existente
$cita = $controller->obtenerCita($id_cita);
if (!$cita) {
    header('Location: index.php?page=citas');
    exit();
}

// Traer clientes, barberos y servicios para llenar los selects
$clientes = $db->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$barberos = $db->query("SELECT * FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $db->query("SELECT * FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

// Inicializar variables de estado
$error = '';
$success = false;

// Endpoint para obtener citas existentes (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['barbero_id']) && isset($_GET['fecha'])) {
    header('Content-Type: application/json');
    
    $barbero_id = $_GET['barbero_id'];
    $fecha = $_GET['fecha'];
    
    try {
        $stmt = $db->prepare("
            SELECT hora_cita 
            FROM citas 
            WHERE id_barbero = :barbero_id 
            AND fecha_cita = :fecha 
            AND estado != 'cancelada'
            AND id_cita != :cita_id
        ");
        
        $stmt->bindParam(':barbero_id', $barbero_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':cita_id', $id_cita);
        $stmt->execute();
        
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($citas);
        exit;
        
    } catch (PDOException $e) {
        echo json_encode([]);
        exit;
    }
}

// Función para generar horas disponibles según barbero y fecha
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

// Función para formatear hora a formato 12 horas (AM/PM)
function formato12Horas($hora) {
    return date('h:i A', strtotime($hora));
}

// Calcular horas disponibles
$horasDisponibles = [];
$barberoSeleccionado = $_POST['id_barbero'] ?? $cita['id_barbero'];
$fechaSeleccionada = $_POST['fecha_cita'] ?? $cita['fecha_cita'];

if ($barberoSeleccionado && $fechaSeleccionada) {
    // Obtener horas ocupadas
    try {
        $stmt = $db->prepare("
            SELECT hora_cita 
            FROM citas 
            WHERE id_barbero = :barbero_id 
            AND fecha_cita = :fecha 
            AND estado != 'cancelada'
            AND id_cita != :cita_id
        ");
        
        $stmt->bindParam(':barbero_id', $barberoSeleccionado);
        $stmt->bindParam(':fecha', $fechaSeleccionada);
        $stmt->bindParam(':cita_id', $id_cita);
        $stmt->execute();
        
        $ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Excluir la hora actual de la cita para que siga disponible
        $ocupadas = array_filter($ocupadas, fn($h) => $h !== $cita['hora_cita']);
        $horasDisponibles = generarHorasDisponibles($barberoSeleccionado, $fechaSeleccionada, $ocupadas);
    } catch (PDOException $e) {
        $error = "Error al obtener horas disponibles: " . $e->getMessage();
    }
}

// Actualizar cita al enviar el formulario (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    
    $id_cliente = $_POST['id_cliente'] ?? '';
    $id_barbero = $_POST['id_barbero'] ?? '';
    $id_servicio = $_POST['id_servicio'] ?? '';
    $fecha_cita = $_POST['fecha_cita'] ?? '';
    $hora_cita = $_POST['hora_cita'] ?? '';

    if ($id_cliente && $id_barbero && $id_servicio && $fecha_cita && $hora_cita) {
        // VERIFICAR PRIMERO SI LA HORA YA ESTÁ OCUPADA (excluyendo la cita actual)
        try {
            $stmt = $db->prepare("
                SELECT COUNT(*) as count 
                FROM citas 
                WHERE id_barbero = :barbero_id 
                AND fecha_cita = :fecha 
                AND hora_cita = :hora 
                AND estado != 'cancelada'
                AND id_cita != :cita_id
            ");
            
            $stmt->bindParam(':barbero_id', $id_barbero);
            $stmt->bindParam(':fecha', $fecha_cita);
            $stmt->bindParam(':hora', $hora_cita);
            $stmt->bindParam(':cita_id', $id_cita);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                // Obtener nombre del barbero para el mensaje de error
                $stmtBarbero = $db->prepare("SELECT nombre FROM barberos WHERE id_barbero = ?");
                $stmtBarbero->execute([$id_barbero]);
                $barbero = $stmtBarbero->fetch(PDO::FETCH_ASSOC);
                $barberoNombre = $barbero['nombre'] ?? 'el barbero';
                
                $error = "{$barberoNombre} ya tiene una cita programada para el {$fecha_cita} a las " . formato12Horas($hora_cita) . ". Por favor, elige otra hora.";
            } else {
                // Si no está ocupada, proceder a actualizar la cita
                $ok = $controller->actualizarCita($id_cita, $id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);
                if ($ok) {
                    $success = true;
                    // Redirigir después de actualizar
                    header('Location: index.php?page=citas&edit_success=1');
                    exit;
                } else {
                    $error = "Error al actualizar la cita en la base de datos.";
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = "Por favor completa todos los campos.";
    }
}
?>
<body>
    <div class="container py-5" style="margin-top:80px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 30px;">

            <?php if($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form id="citaForm" method="POST">
                <input type="hidden" name="id_cita" value="<?= $id_cita ?>">
                
                <div class="mb-3">
                    <label class="form-label">Cliente</label>
                    <select name="id_cliente" class="form-control" id="cliente" required>
                        <option value="">Selecciona un cliente</option>
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['id_cliente'] ?>" <?= ($c['id_cliente'] == $cita['id_cliente']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="barbero" class="form-label">Barbero</label>
                        <select name="id_barbero" id="barbero" class="form-select" required onchange="this.form.submit()">
                            <option value="">Selecciona un barbero</option>
                            <?php foreach($barberos as $b): ?>
                                <option value="<?= $b['id_barbero'] ?>" <?= ($b['id_barbero'] == $barberoSeleccionado) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($b['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="servicio" class="form-label">Servicio</label>
                        <select name="id_servicio" id="servicio" class="form-select" required>
                            <option value="">Selecciona un servicio</option>
                            <?php foreach($servicios as $s): ?>
                                <option value="<?= $s['id_servicio'] ?>" data-img="<?= htmlspecialchars($s['img_servicio']) ?>" <?= ($s['id_servicio'] == $cita['id_servicio']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_cita" class="form-label">Fecha</label>
                        <input name="fecha_cita" id="fecha_cita" type="date" class="form-control" value="<?= $fechaSeleccionada ?>" required onchange="this.form.submit()">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="hora_cita" class="form-label">Hora</label>
                        <select name="hora_cita" id="hora_cita" class="form-select" required>
                            <!-- Hora actual de la cita -->
                            <option value="<?= $cita['hora_cita'] ?>" selected>
                                <?= formato12Horas($cita['hora_cita']) ?>
                            </option>
                            <!-- Horas disponibles -->
                            <?php foreach($horasDisponibles as $h): ?>
                                <option value="<?= $h ?>"><?= formato12Horas($h) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3 text-center" id="servicePreview">
                    <?php 
                    // Obtener la imagen del servicio actual
                    $img_servicio = '';
                    foreach ($servicios as $s) {
                        if ($s['id_servicio'] == $cita['id_servicio']) {
                            $img_servicio = $s['img_servicio'];
                            break;
                        }
                    }
                    if (!empty($img_servicio)): ?>
                        <img id="serviceImg" src="app/uploads/servicios/<?= $img_servicio ?>" alt="Imagen del servicio" class="img-fluid mb-2"
                            style="max-width:150px; max-height:180px; border: 3px solid #00ff00; border-radius: 8px;"/>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-2 justify-content-between mt-4">
                    <a href="index.php?page=citas" class="btn btn-danger w-25">Cancelar</a>
                    <button type="submit" class="btn btn-neon w-25">Actualizar</button>
                </div>
            </form>

            <div class="w-100 bg-dark p-3 rounded mt-4 border border-secondary">
                <h4 class="mb-2 text-white">
                    <i class="far fa-clock me-2"></i><strong>Horario de Atención</strong>
                </h4>
                <p class="mb-1">Lunes a sábado: 08:00 AM - 08:00 PM</p>
                <p class="mb-0">Domingos: 09:00 AM - 04:00 PM</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const servicioSelect = document.getElementById('servicio');
            const servicePreview = document.getElementById('servicePreview');
            const serviceImg = document.getElementById('serviceImg');
            
            // Mostrar imagen del servicio seleccionado
            function actualizarImagenServicio() {
                const selectedOption = servicioSelect.options[servicioSelect.selectedIndex];
                const imgSrc = selectedOption.getAttribute('data-img');
                
                if (imgSrc) {
                    if (!serviceImg) {
                        // Crear elemento de imagen si no existe
                        const img = document.createElement('img');
                        img.id = 'serviceImg';
                        img.alt = 'Imagen del servicio';
                        img.className = 'img-fluid mb-2';
                        img.style = 'max-width:150px; max-height:180px; border: 3px solid #00ff00; border-radius: 8px;';
                        img.src = 'app/uploads/servicios/' + imgSrc;
                        servicePreview.innerHTML = '';
                        servicePreview.appendChild(img);
                    } else {
                        serviceImg.src = 'app/uploads/servicios/' + imgSrc;
                    }
                    servicePreview.style.display = 'block';
                } else {
                    servicePreview.style.display = 'none';
                }
            }
            
            // Event listener para cambio de servicio
            servicioSelect.addEventListener('change', actualizarImagenServicio);
            
            // Inicializar
            actualizarImagenServicio();
            
            // Validación de fecha (no permitir fechas pasadas)
            const fechaInput = document.getElementById('fecha_cita');
            const hoy = new Date().toISOString().split('T')[0];
            fechaInput.min = hoy;
        });
    </script>
<main>