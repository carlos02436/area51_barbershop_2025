<?php
// Conexión a BD
$pdo = new PDO("mysql:host=localhost;dbname=area51_barbershop_2025;charset=utf8", "root", "");

// Obtener datos básicos
$clientes = $pdo->query("SELECT id_cliente, nombre, apellido FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$barberos = $pdo->query("SELECT * FROM barberos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $pdo->query("SELECT * FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

// Generar horarios
function generarHorarios()
{
    $horarios = [];
    $inicio = strtotime("08:00");
    $fin = strtotime("21:00");

    for ($hora = $inicio; $hora <= $fin; $hora = strtotime("+1 hour", $hora)) {
        $horaStr = date("H:i", $hora);

        // Excluir almuerzo
        if ($horaStr >= "12:00" && $horaStr < "13:00") continue;

        $horarios[] = $horaStr;
    }
    return $horarios;
}
$horariosDisponibles = generarHorarios();
?>

<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Nueva Cita</h1>

        <form action="index.php?page=crear" method="POST" class="text-white p-4 rounded shadow-sm mx-auto" style="max-width: 600px;">
            <!-- CLIENTE -->
            <div class="mb-3">
                <label class="form-label fw-bold">¿El cliente está registrado?</label><br>
                <input type="radio" name="tipo_cliente" value="registrado" checked> Sí
                <input type="radio" name="tipo_cliente" value="nuevo" class="ms-3"> No
            </div>

            <!-- Cliente registrado -->
            <div id="clienteRegistrado" class="mb-3">
                <label for="id_cliente" class="form-label">Seleccionar cliente</label>
                <select name="id_cliente" id="id_cliente" class="form-select">
                    <option value="">Seleccionar cliente existente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id_cliente'] ?>">
                            <?= htmlspecialchars($cliente['nombre'] . " " . $cliente['apellido']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Cliente nuevo -->
            <div id="clienteNuevo" class="mb-3 d-none">
                <label class="form-label">Registrar nuevo cliente</label>
                <input type="text" name="nuevo_nombre" class="form-control mb-2" placeholder="Nombre" required>
                <input type="text" name="nuevo_apellido" class="form-control mb-2" placeholder="Apellido" required>
                <input type="text" name="nuevo_telefono" class="form-control mb-2" placeholder="Teléfono">
                <input type="email" name="nuevo_correo" class="form-control" placeholder="Correo">
            </div>

            <!-- Cliente nuevo -->
            <script>
                document.querySelectorAll('input[name="tipo_cliente"]').forEach(radio => {
                    radio.addEventListener('change', () => {
                        const clienteRegistrado = document.getElementById('clienteRegistrado');
                        const clienteNuevo = document.getElementById('clienteNuevo');

                        if (radio.value === 'registrado') {
                            clienteRegistrado.classList.remove('d-none');
                            clienteNuevo.classList.add('d-none');
                        } else {
                            clienteRegistrado.classList.add('d-none');
                            clienteNuevo.classList.remove('d-none');
                        }
                    });
                });
            </script>


            <!-- BARBERO -->
            <div class="mb-3">
                <label for="id_barbero" class="form-label">Barbero</label>
                <select name="id_barbero" id="id_barbero" class="form-select" required>
                    <option value="">Seleccionar barbero</option>
                    <?php foreach ($barberos as $barbero): ?>
                        <option value="<?= $barbero['id_barbero'] ?>"><?= htmlspecialchars($barbero['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- SERVICIO -->
            <div class="mb-3">
                <label for="id_servicio" class="form-label">Servicio</label>
                <select name="id_servicio" id="id_servicio" class="form-select" required>
                    <option value="">Seleccionar servicio</option>
                    <?php foreach ($servicios as $servicio): ?>
                        <option value="<?= $servicio['id_servicio'] ?>"><?= htmlspecialchars($servicio['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- FECHA -->
            <div class="mb-3">
                <label for="fecha_cita" class="form-label">Fecha</label>
                <input type="date" name="fecha_cita" id="fecha_cita" class="form-control" min="<?= date('Y-m-d') ?>" required>
            </div>

            <!-- HORA -->
            <div class="mb-3">
                <label for="hora_cita" class="form-label">Hora</label>
                <select name="hora_cita" id="hora_cita" class="form-select" required>
                    <option value="">Seleccionar hora</option>
                    <?php foreach ($horariosDisponibles as $hora): ?>
                        <option value="<?= $hora ?>"><?= date("g:i A", strtotime($hora)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- ESTADO -->
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="cancelada">Cancelada</option>
                    <option value="realizada">Realizada</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php?page=home#contacto" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-neon">Guardar</button>
            </div>
        </form>
    </div>

    <!-- SCRIPT PARA VALIDAR HORAS OCUPADAS -->
    <script>
        document.getElementById('id_barbero').addEventListener('change', verificarDisponibilidad);
        document.getElementById('fecha_cita').addEventListener('change', verificarDisponibilidad);

        function verificarDisponibilidad() {
            const barbero = document.getElementById('id_barbero').value;
            const fecha = document.getElementById('fecha_cita').value;
            const horaSelect = document.getElementById('hora_cita');

            if (!barbero || !fecha) return;

            fetch(`ajax_disponibilidad.php?barbero=${barbero}&fecha=${fecha}`)
                .then(res => res.json())
                .then(data => {
                    [...horaSelect.options].forEach(opt => {
                        if (data.ocupadas.includes(opt.value)) {
                            opt.disabled = true;
                            opt.textContent = opt.textContent.replace(" (Ocupado)", "") + " (Ocupado)";
                        } else {
                            opt.disabled = false;
                            opt.textContent = opt.textContent.replace(" (Ocupado)", "");
                        }
                    });
                });
        }
    </script>
    <main>