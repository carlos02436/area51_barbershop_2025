<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Cita</h1>

        <form action="index.php?page=editar_cita&id=<?= $cita['id_cita'] ?>" 
              method="POST" 
              class="text-white p-4 rounded shadow-sm mx-auto" 
              style="max-width: 600px;">
              
            <div class="mb-3">
                <label for="id_cliente" class="form-label">Cliente</label>
                <select name="id_cliente" id="id_cliente" class="form-select" required>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id_cliente'] ?>" <?= $cita['id_cliente'] == $cliente['id_cliente'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cliente['nombre_completo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_barbero" class="form-label">Barbero</label>
                <select name="id_barbero" id="id_barbero" class="form-select" required>
                    <?php foreach ($barberos as $barbero): ?>
                        <option value="<?= $barbero['id_barbero'] ?>" <?= $cita['id_barbero'] == $barbero['id_barbero'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($barbero['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_servicio" class="form-label">Servicio</label>
                <select name="id_servicio" id="id_servicio" class="form-select" required>
                    <?php foreach ($servicios as $servicio): ?>
                        <option value="<?= $servicio['id_servicio'] ?>" <?= $cita['id_servicio'] == $servicio['id_servicio'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($servicio['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_cita" class="form-label">Fecha</label>
                <input type="date" name="fecha_cita" id="fecha_cita" class="form-control" value="<?= $cita['fecha_cita'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="hora_cita" class="form-label">Hora</label>
                <input type="time" name="hora_cita" id="hora_cita" class="form-control" value="<?= $cita['hora_cita'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="pendiente" <?= $cita['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="confirmada" <?= $cita['estado'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                    <option value="cancelada" <?= $cita['estado'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    <option value="realizada" <?= $cita['estado'] == 'realizada' ? 'selected' : '' ?>>Realizada</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php?page=citas" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-neon">Actualizar</button>
            </div>
        </form>
    </div>
<main>