<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/CitasController.php';
require_once __DIR__ . '/../../../config/database.php';

$controller = new CitasController($db);

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
        ");
        
        $stmt->bindParam(':barbero_id', $barbero_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($citas);
        exit;
        
    } catch (PDOException $e) {
        echo json_encode([]);
        exit;
    }
}

// Refresh horas function - NUEVA FUNCIÓN AÑADIDA
function refreshHoras() {
    global $db;
    
    $barberoId = isset($_POST['barbero_id']) ? $_POST['barbero_id'] : '';
    $fechaISO = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    
    if (!$barberoId || !$fechaISO) {
        return json_encode([]);
    }

    try {
        // Obtener citas existentes del barbero para esta fecha
        $stmt = $db->prepare("
            SELECT hora_cita 
            FROM citas 
            WHERE id_barbero = :barbero_id 
            AND fecha_cita = :fecha 
            AND estado != 'cancelada'
        ");
        
        $stmt->bindParam(':barbero_id', $barberoId);
        $stmt->bindParam(':fecha', $fechaISO);
        $stmt->execute();
        
        $citasExistentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Generar slots disponibles según horario (simplificado)
        $slotsDisponibles = [];
        $horasOcupadas = array_column($citasExistentes, 'hora_cita');
        
        // Lógica para generar horarios disponibles (9:00 a 19:00)
        for ($h = 9; $h < 19; $h++) {
            $horaSlot = sprintf("%02d:00:00", $h);
            if (!in_array($horaSlot, $horasOcupadas)) {
                $slotsDisponibles[] = $horaSlot;
            }
        }
        
        return json_encode($slotsDisponibles);
        
    } catch (PDOException $e) {
        return json_encode([]);
    }
}

// Guardar cita al enviar el formulario (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si es una solicitud para refrescar horas
    if (isset($_POST['action']) && $_POST['action'] === 'refresh_horas') {
        header('Content-Type: application/json');
        echo refreshHoras();
        exit;
    }
    
    $id_cliente = $_POST['id_cliente'] ?? '';
    $id_barbero = $_POST['id_barbero'] ?? '';
    $id_servicio = $_POST['id_servicio'] ?? '';
    $fecha_cita = $_POST['fecha_cita'] ?? '';
    $hora_cita = $_POST['hora_cita'] ?? '';

    if ($id_cliente && $id_barbero && $id_servicio && $fecha_cita && $hora_cita) {
        $servicio = $controller->getServicio($id_servicio);
        $img_servicio = $servicio['img_servicio'] ?? '';

        // VERIFICAR PRIMERO SI LA HORA YA ESTÁ OCUPADA
        try {
            $stmt = $db->prepare("
                SELECT COUNT(*) as count 
                FROM citas 
                WHERE id_barbero = :barbero_id 
                AND fecha_cita = :fecha 
                AND hora_cita = :hora 
                AND estado != 'cancelada'
            ");
            
            $stmt->bindParam(':barbero_id', $id_barbero);
            $stmt->bindParam(':fecha', $fecha_cita);
            $stmt->bindParam(':hora', $hora_cita);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                // Obtener nombre del barbero para el mensaje de error
                $stmtBarbero = $db->prepare("SELECT nombre FROM barberos WHERE id_barbero = ?");
                $stmtBarbero->execute([$id_barbero]);
                $barbero = $stmtBarbero->fetch(PDO::FETCH_ASSOC);
                $barberoNombre = $barbero['nombre'] ?? 'el barbero';
                
                throw new Exception("{$barberoNombre} ya tiene una cita programada para el {$fecha_cita} a las {$hora_cita}. Por favor, elige otra hora.");
            }
            
            // Si no está ocupada, proceder a crear la cita
            $ok = $controller->crear($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita, $img_servicio);
            if ($ok) {
                $success = true;
                header('Content-Type: application/json');
                echo json_encode(['ok' => true]);
                exit;
            } else {
                $error = "Error al crear la cita en la base de datos.";
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'error' => $error]);
                exit;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => $error]);
            exit;
        }
    } else {
        $error = "Por favor completa todos los campos.";
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => $error]);
        exit;
    }
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">

            <?php if($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif($success): ?>
                <div class="alert alert-success">¡Cita creada correctamente!</div>
            <?php endif; ?>

            <form id="citaForm" method="POST" novalidate>
                <div class="mb-3">
                    <label>Cliente</label>
                    <select name="id_cliente" class="form-control w-100" id="cliente" required>
                        <option value="">Selecciona un cliente</option>
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['id_cliente'] ?>">
                                <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="barbero" class="form-label">Barbero</label>
                        <select name="id_barbero" id="barbero" class="form-select" required>
                            <option value="">Selecciona un barbero</option>
                            <?php foreach($barberos as $b): ?>
                                <option value="<?= $b['id_barbero'] ?>"><?= htmlspecialchars($b['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="barberoNotice" class="form-text text-white"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="servicio" class="form-label">Servicio</label>
                        <select name="id_servicio" id="servicio" class="form-select" required>
                            <option value="">Selecciona un servicio</option>
                            <?php foreach($servicios as $s): ?>
                                <option value="<?= $s['id_servicio'] ?>" data-img="<?= htmlspecialchars($s['img_servicio']) ?>">
                                    <?= htmlspecialchars($s['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input name="fecha_cita" id="fecha" type="date" class="form-control" required>
                        <div id="fechaErrors" class="form-text text-danger"></div>
                    </div>

                    <div class="col-md-6 mb-5">
                        <label for="hora" class="form-label">Hora disponible</label>
                        <select name="hora_cita" id="hora" class="form-select" required>
                            <option value="">Selecciona una hora</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3" id="servicePreview" hidden>
                    <img id="serviceImg" alt="Imagen del servicio" class="img-fluid mb-2"
                        style="max-width:120px; max-height:140px; border: 3px solid #00ff00; border-radius: 8px;"/>
                    <div class="details">
                        <span id="serviceName" class="fw-bold"></span>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-between">
                    <button type="reset" class="btn btn-danger" id="resetBtn">Limpiar</button>
                    <button type="submit" class="btn btn-neon">Guardar y enviar WhatsApp</button>
                </div>
            </form>

            <div class="w-100 bg-info p-3 rounded mt-4">
                <h4 class="mb-2 text-white">
                    <i class="far fa-clock me-2"></i><strong>Horario de Atención</strong>
                </h4>
                <p class="mb-1">Lunes a sábado: 08:00 AM - 08:00 PM</p>
                <p class="mb-0">Domingos: 09:00 AM - 04:00 PM</p>
            </div>
        </div>
    </div>

    <script type="importmap">
    {
    "imports": {
        "dayjs": "https://esm.sh/dayjs@1.11.11",
        "dayjs/plugin/isoWeek": "https://esm.sh/dayjs@1.11.11/plugin/isoWeek",
        "dayjs/plugin/customParseFormat": "https://esm.sh/dayjs@1.11.11/plugin/customParseFormat"
    }
    }
    </script>
    <script type="module" src="public/JavaScript/app.js"></script>
<main>