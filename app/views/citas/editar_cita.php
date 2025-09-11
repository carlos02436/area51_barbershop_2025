<?php
// Variables esperadas: $cita, $clientes, $barberos, $servicios
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">

            <form action="index.php?page=actualizar_cita" method="POST">
                <!-- ID oculto -->
                <input type="hidden" name="id_cita" value="<?= htmlspecialchars($cita['id_cita']) ?>">

                <!-- Cliente -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Cliente</label>
                    <select name="id_cliente" class="form-select" required>
                        <?php foreach ($clientes as $clienteItem): ?>
                            <option value="<?= $clienteItem['id_cliente'] ?>" 
                                <?= $clienteItem['id_cliente'] == $cita['id_cliente'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($clienteItem['nombre'] . ' ' . $clienteItem['apellido']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Servicio -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Servicio</label>
                    <select name="id_servicio" class="form-select" required>
                        <?php foreach ($servicios as $servicioItem): ?>
                            <option value="<?= $servicioItem['id_servicio'] ?>"
                                <?= $servicioItem['id_servicio'] == $cita['id_servicio'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($servicioItem['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imagen del servicio -->
                <div class="mb-3 text-center">
                    <?php
                    // Buscar el servicio seleccionado para mostrar su imagen
                    $servicioSeleccionado = null;
                    foreach ($servicios as $servicioItem) {
                        if ($servicioItem['id_servicio'] == $cita['id_servicio']) {
                            $servicioSeleccionado = $servicioItem;
                            break;
                        }
                    }
                    ?>

                    <?php if ($servicioSeleccionado && !empty($servicioSeleccionado['img_servicio'])): ?>
                        <p class="text-success fw-bold mb-2 text-white">Imagen del servicio seleccionado:</p>
                        <img src="app/uploads/servicios/<?= htmlspecialchars($servicioSeleccionado['img_servicio']) ?>"
                             alt="Imagen del servicio"
                             class="img-thumbnail border-success"
                             style="max-width:200px;">
                    <?php else: ?>
                        <p class="text-warning small">⚠️ Sin imágen del servicio</p>
                    <?php endif; ?>
                </div>

                <!-- Fecha -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha_cita" class="form-control"
                        value="<?= htmlspecialchars($cita['fecha_cita']) ?>" required>
                </div>

                <!-- Hora -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Hora</label>
                    <input type="time" name="hora_cita" class="form-control"
                        value="<?= htmlspecialchars($cita['hora_cita']) ?>" required>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between w-100 mx-auto">
                    <a href="index.php?page=citas" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Actualizar Cita</button>
                </div>

            </form>
        </div>
    </div>
<main>