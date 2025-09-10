<body>
<div class="container py-5" style="margin-top:100px;">
    <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Cita</h1>
    <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
        <form action="index.php?page=editar_cita&id=<?= $cita['id_cita'] ?>" 
              method="POST" 
              class="text-white">

            <!-- Cliente -->
            <div class="mb-4">
                <label for="id_cliente" class="form-label">Cliente</label>
                <select name="id_cliente" id="id_cliente" class="form-select form-select-lg w-100" required>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id_cliente'] ?>" <?= $cita['id_cliente'] == $cliente['id_cliente'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cliente['nombre_completo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Barbero -->
            <div class="mb-4">
                <label for="id_barbero" class="form-label">Barbero</label>
                <select name="id_barbero" id="id_barbero" class="form-select form-select-lg w-100" required>
                    <?php foreach ($barberos as $barbero): ?>
                        <option value="<?= $barbero['id_barbero'] ?>" <?= $cita['id_barbero'] == $barbero['id_barbero'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($barbero['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Servicio -->
            <div class="mb-4">
                <label for="id_servicio" class="form-label">Servicio</label>
                <select name="id_servicio" id="id_servicio" class="form-select form-select-lg w-100" required>
                    <?php foreach ($servicios as $servicio): ?>
                        <option value="<?= $servicio['id_servicio'] ?>" <?= $cita['id_servicio'] == $servicio['id_servicio'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($servicio['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Imagen del servicio actual -->
                <?php 
                $servicioSeleccionado = null;
                foreach ($servicios as $s) {
                    if ($s['id_servicio'] == $cita['id_servicio']) {
                        $servicioSeleccionado = $s;
                        break;
                    }
                }
                if ($servicioSeleccionado && !empty($servicioSeleccionado['img_servicio'])): ?>
                    <div class="text-center mt-3">
                        <p class="text-white mb-2">Imagen del servicio actual:</p>
                        <img src="app/uploads/servicios/<?= htmlspecialchars($servicioSeleccionado['img_servicio']) ?>" 
                             alt="Imagen servicio" 
                             class="img-fluid rounded shadow"
                             style="max-height: 200px;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Fecha -->
            <div class="mb-4">
                <label for="fecha_cita" class="form-label">Fecha</label>
                <input type="date" name="fecha_cita" id="fecha_cita" class="form-control form-control-lg w-100" value="<?= $cita['fecha_cita'] ?>" required>
            </div>

            <!-- Hora -->
            <div class="mb-4">
                <label for="hora_cita" class="form-label">Hora</label>
                <input type="time" name="hora_cita" id="hora_cita" class="form-control form-control-lg w-100" value="<?= $cita['hora_cita'] ?>" required>
            </div>

            <!-- Estado -->
            <div class="mb-4">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select form-select-lg w-100">
                    <option value="pendiente" <?= $cita['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="confirmada" <?= $cita['estado'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                    <option value="cancelada" <?= $cita['estado'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    <option value="realizada" <?= $cita['estado'] == 'realizada' ? 'selected' : '' ?>>Realizada</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <a href="index.php?page=citas" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-neon">Actualizar</button>
            </div>
        </form>
    </div>
</div>
<main>