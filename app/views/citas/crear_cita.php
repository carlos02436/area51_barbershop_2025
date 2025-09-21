<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/CitasController.php';

$controller = new CitasController($db);

// Traer clientes, barberos y servicios
$clientes = $db->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$barberos = $db->query("SELECT * FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $db->query("SELECT * FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = false;
$horasDisponibles = [];
$citaData = [];

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

// 🔴 PRIMERO: Verificar si es una solicitud AJAX para horas
if (isset($_POST['ajax_horas']) && $_POST['ajax_horas'] === 'true') {
    $id_barbero = $_POST['id_barbero'] ?? '';
    $fecha_cita = $_POST['fecha_cita'] ?? '';
    
    if (!empty($id_barbero) && !empty($fecha_cita)) {
        $ocupadas = $controller->horasOcupadas($id_barbero, $fecha_cita);
        $horasDisponibles = generarHoras($id_barbero, $fecha_cita, $ocupadas);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'horas' => $horasDisponibles]);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        exit;
    }
}

// 🔴 SEGUNDO: Verificar si es envío del formulario principal con AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_barbero = $_POST['id_barbero'];
    $id_servicio = $_POST['id_servicio'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];

    if(!empty($id_cliente) && !empty($id_barbero) && !empty($id_servicio) && !empty($fecha_cita) && !empty($hora_cita)) {
        $ok = $controller->crearCita($id_cliente, $id_barbero, $id_servicio, $fecha_cita, $hora_cita);

        if ($ok) {
            $success = true;
            
            // Preparar datos para WhatsApp
            $servicio = $servicios[array_search($id_servicio, array_column($servicios, 'id_servicio'))];
            $cliente = $clientes[array_search($id_cliente, array_column($clientes, 'id_cliente'))];
            $barbero = $barberos[array_search($id_barbero, array_column($barberos, 'id_barbero'))];

            $citaData = [
                'cliente' => [
                    'nombre' => $cliente['nombre'] . ' ' . $cliente['apellido'],
                    'telefono' => $cliente['telefono']
                ],
                'barbero' => [
                    'nombre' => $barbero['nombre'],
                    'telefono' => $barbero['telefono']
                ],
                'servicio' => [
                    'nombre' => $servicio['nombre'],
                    'precio' => $servicio['precio']
                ],
                'fecha' => date('d/m/Y', strtotime($fecha_cita)),
                'hora' => date('h:i A', strtotime($hora_cita))
            ];

            // Si es AJAX, devolver JSON y terminar
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'cita' => $citaData]);
                exit;
            }
        }
    }
}

// 🔴 TERCERO: Calcular horas disponibles para recarga normal (no AJAX)
if (isset($_POST['id_barbero'], $_POST['fecha_cita']) && !isset($_POST['ajax_horas'])) {
    $ocupadas = $controller->horasOcupadas($_POST['id_barbero'], $_POST['fecha_cita']);
    $horasDisponibles = generarHoras($_POST['id_barbero'], $_POST['fecha_cita'], $ocupadas);
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <?php if(isset($error) && $error) echo "<p class='text-danger'>$error</p>"; ?>
            <?php if(isset($success) && $success) echo "<p class='text-success'>Cita creada exitosamente!</p>"; ?>
            
            <form id="citaForm" method="POST" action="">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_cliente" class="form-label">Cliente</label>
                            <select class="form-select" id="id_cliente" name="id_cliente" required>
                                <option value="">Seleccionar cliente</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?= $cliente['id_cliente'] ?>">
                                        <?= $cliente['nombre'] . ' ' . $cliente['apellido'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_servicio" class="form-label">Servicio</label>
                            <select class="form-select" id="id_servicio" name="id_servicio" required>
                                <option value="">Seleccionar servicio</option>
                                <?php foreach ($servicios as $servicio): ?>
                                    <option value="<?= $servicio['id_servicio'] ?>">
                                        <?= $servicio['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_barbero" class="form-label">Barbero</label>
                            <select class="form-select" id="id_barbero" name="id_barbero" required>
                                <option value="">Seleccionar barbero</option>
                                <?php foreach ($barberos as $barbero): ?>
                                    <option value="<?= $barbero['id_barbero'] ?>">
                                        <?= $barbero['nombre'] ?>
                                        <?= ($barbero['id_barbero'] == 1) ? " (No trabaja domingos)" : "" ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_cita" class="form-label">Fecha de la cita</label>
                            <input type="date" class="form-control" id="fecha_cita" name="fecha_cita" required>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Horario de atención:</strong> Lunes a Sábado: 8:00 AM - 8:00 PM | Domingos: 9:00 AM - 4:00 PM
                </div>

                <div id="loading" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Buscando horarios disponibles...</p>
                </div>

                <div id="horarios-container" class="d-none">
                    <h5 class="mb-3">Horarios disponibles</h5>
                    <div class="alert alert-warning d-none" id="barberoWarning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span id="warningText"></span>
                    </div>
                    <div id="horarios-list" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2 mb-4"></div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Confirmar Cita
                        </button>
                    </div>
                </div>

                <div id="no-horarios" class="text-center py-4 d-none">
                    <div class="alert alert-warning">
                        <i class="bi bi-clock-history me-2"></i>
                        No hay horarios disponibles para la fecha y barbero seleccionados.
                    </div>
                </div>
                
                <!-- Campo oculto para la hora seleccionada -->
                <input type="hidden" id="hora_cita" name="hora_cita" value="">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Establecer fecha mínima como hoy
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const todayStr = `${yyyy}-${mm}-${dd}`;
            document.getElementById('fecha_cita').setAttribute('min', todayStr);
            
            // Elementos del DOM
            const barberoSelect = document.getElementById('id_barbero');
            const fechaInput = document.getElementById('fecha_cita');
            const loadingElement = document.getElementById('loading');
            const horariosContainer = document.getElementById('horarios-container');
            const noHorariosElement = document.getElementById('no-horarios');
            const horariosList = document.getElementById('horarios-list');
            const barberoWarning = document.getElementById('barberoWarning');
            const warningText = document.getElementById('warningText');
            const horaInput = document.getElementById('hora_cita');
            
            // Event listeners
            barberoSelect.addEventListener('change', verificarDisponibilidad);
            fechaInput.addEventListener('change', verificarDisponibilidad);
            
            // Función para verificar disponibilidad
            function verificarDisponibilidad() {
                const idBarbero = barberoSelect.value;
                const fechaCita = fechaInput.value;
                
                if (!idBarbero || !fechaCita) {
                    return;
                }
                
                // Mostrar carga
                loadingElement.classList.remove('d-none');
                horariosContainer.classList.add('d-none');
                noHorariosElement.classList.add('d-none');
                
                // Hacer petición AJAX al servidor para obtener horas disponibles
                fetchHorariosDisponibles(idBarbero, fechaCita)
                    .then(horariosDisponibles => {
                        // Ocultar carga
                        loadingElement.classList.add('d-none');
                        
                        if (horariosDisponibles.length > 0) {
                            mostrarHorariosDisponibles(horariosDisponibles, fechaCita);
                        } else {
                            noHorariosElement.classList.remove('d-none');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        loadingElement.classList.add('d-none');
                        alert('Error al obtener los horarios disponibles. Por favor, recarga la página e intenta nuevamente.');
                    });
            }
            
            // Función para obtener horarios disponibles desde el servidor
            function fetchHorariosDisponibles(idBarbero, fechaCita) {
                return new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append('ajax_horas', 'true');
                    formData.append('id_barbero', idBarbero);
                    formData.append('fecha_cita', fechaCita);
                    
                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            resolve(data.horas);
                        } else {
                            reject(data.error || 'Error desconocido');
                        }
                    })
                    .catch(error => reject(error));
                });
            }
            
            // Función para mostrar horarios disponibles
            function mostrarHorariosDisponibles(horarios, fechaCita) {
                horariosList.innerHTML = '';
                horaInput.value = '';
                
                // Obtener la hora actual
                const ahora = new Date();
                const horaActual = ahora.getHours();
                const minutosActual = ahora.getMinutes();
                const fechaSeleccionada = new Date(fechaCita);
                const esHoy = fechaSeleccionada.toDateString() === ahora.toDateString();
                
                // Verificar si hay horarios disponibles
                if (horarios.length === 0) {
                    noHorariosElement.classList.remove('d-none');
                    horariosContainer.classList.add('d-none');
                    return;
                }
                
                horarios.forEach(hora => {
                    // Convertir hora de formato HH:MM:SS a formato legible
                    const [h, m, s] = hora.split(':');
                    const horaNum = parseInt(h);
                    const minutoNum = parseInt(m);
                    const periodo = horaNum >= 12 ? 'PM' : 'AM';
                    const hora12 = horaNum % 12 || 12;
                    const horaFormateada = `${hora12}:${minutoNum === 0 ? '00' : minutoNum} ${periodo}`;
                    
                    // Verificar si la hora ya pasó (si es hoy)
                    let disponible = true;
                    if (esHoy) {
                        if (horaNum < horaActual) {
                            disponible = false;
                        } else if (horaNum === horaActual && minutoNum < minutosActual) {
                            disponible = false;
                        }
                    }
                    
                    const col = document.createElement('div');
                    col.className = 'col';
                    
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = `btn ${disponible ? 'btn-outline-primary available' : 'btn-outline-secondary unavailable'} time-slot w-100 mb-2`;
                    button.textContent = horaFormateada;
                    button.dataset.hora = hora;
                    
                    if (!disponible) {
                        button.disabled = true;
                        button.title = 'Esta hora ya ha pasado';
                    } else {
                        button.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot').forEach(btn => {
                                btn.classList.remove('btn-primary');
                                btn.classList.add('btn-outline-primary');
                            });
                            this.classList.remove('btn-outline-primary');
                            this.classList.add('btn-primary');
                            
                            // Guardar la hora seleccionada en el campo oculto
                            horaInput.value = hora;
                        });
                    }
                    
                    col.appendChild(button);
                    horariosList.appendChild(col);
                });
                
                horariosContainer.classList.remove('d-none');
                noHorariosElement.classList.add('d-none');
            }
            
            // Manejar envío del formulario
            document.getElementById('citaForm').addEventListener('submit', function(e) {
                if (!horaInput.value) {
                    e.preventDefault();
                    alert('Por favor, selecciona un horario para tu cita.');
                    return;
                }
            });
        });
    </script>
<main>