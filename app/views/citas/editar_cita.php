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

                <!-- Barbero -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Barbero</label>
                    <select name="id_barbero" class="form-select" required>
                        <?php foreach ($barberos as $barberoItem): ?>
                            <option value="<?= $barberoItem['id_barbero'] ?>"
                                <?= $barberoItem['id_barbero'] == $cita['id_barbero'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($barberoItem['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Servicio -->
                <div class="mb-3">
                    <label class="form-label">Servicio</label>
                    <select name="id_servicio" class="form-select" id="selectServicio" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($servicios as $s): ?>
                            <option 
                                value="<?= htmlspecialchars($s['id_servicio']) ?>"
                                data-img="app/uploads/servicios/<?= htmlspecialchars($s['img_servicio']) ?>"
                                <?= $cita['id_servicio'] == $s['id_servicio'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imagen servicio -->
                <div class="mb-3 text-center" id="contenedorImgServicio" style="<?= !empty($cita['img_servicio']) ? '' : 'display:none;' ?>">
                    <img id="imgServicio" 
                        src="<?= !empty($cita['img_servicio']) ? 'app/uploads/servicios/' . htmlspecialchars($cita['img_servicio']) : '' ?>" 
                        alt="Imagen Servicio" 
                        class="img-fluid rounded shadow" 
                        style="max-height:200px; width:auto; max-width:100%;">
                    <input type="hidden" name="img_servicio" id="inputImgServicio" value="<?= htmlspecialchars($cita['img_servicio']) ?>">
                </div>

                <script>
                document.getElementById('selectServicio').addEventListener('change', function() {
                    let img = this.options[this.selectedIndex].dataset.img;
                    if (img) {
                        document.getElementById('imgServicio').src = img;
                        document.getElementById('inputImgServicio').value = img.replace("app/uploads/servicios/","");
                        document.getElementById('contenedorImgServicio').style.display = 'block';
                    }
                });
                </script>

                <!-- Fecha -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha_cita" class="form-control"
                        value="<?= htmlspecialchars($cita['fecha_cita']) ?>" required>
                </div>

                <!-- Horas -->
                <div class="mb-3" id="horaContainer">
                    <label class="form-label">Hora</label>
                    <select name="hora_cita" id="selectHora" class="form-select" required
                        data-hora-actual="<?= $cita['hora_cita'] ?? '' ?>">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>

                <script>
                    // ⏰ Simular horas ocupadas (formato "08:00 am", "10:00 am", etc.)
                    const horasOcupadas = [
                        "09:00 am",
                        "11:00 am",
                        "02:00 pm"
                    ];

                    // 📅 Generar horas en punto en formato 12h
                    function generarHoras(inicio, fin) {
                        const horas = [];
                        let [horaInicio, periodoInicio] = inicio.split(' ');
                        let [horaFin, periodoFin] = fin.split(' ');

                        let hInicio = parseInt(horaInicio.split(':')[0]);
                        let hFin = parseInt(horaFin.split(':')[0]);

                        if (periodoInicio.toLowerCase() === 'pm' && hInicio !== 12) hInicio += 12;
                        if (periodoFin.toLowerCase() === 'pm' && hFin !== 12) hFin += 12;
                        if (periodoFin.toLowerCase() === 'am' && hFin === 12) hFin = 0;

                        for (let h = hInicio; h <= hFin; h++) {
                            let periodo = h >= 12 ? 'pm' : 'am';
                            let hora12 = h % 12 === 0 ? 12 : h % 12;
                            let horaFormateada = `${hora12.toString().padStart(2, '0')}:00 ${periodo}`;
                            horas.push(horaFormateada);
                        }

                        return horas;
                    }

                    function cargarHorasSegunDia() {
                        const selectHora = document.getElementById('selectHora');
                        const diaSemana = new Date().getDay(); // 0 = domingo, 1 = lunes, ..., 6 = sábado

                        let horas = [];

                        if (diaSemana === 0) {
                            // Domingo: 09:00 am - 04:00 pm
                            horas = generarHoras("09:00 am", "04:00 pm");
                        } else {
                            // Lunes a sábado: 08:00 am - 08:00 pm
                            horas = generarHoras("08:00 am", "08:00 pm");
                        }

                        // Limpiar el select
                        selectHora.innerHTML = '<option value="">Seleccionar...</option>';

                        // Agregar solo horas disponibles
                        horas.forEach(hora => {
                            if (!horasOcupadas.includes(hora)) {
                                const option = document.createElement('option');
                                option.value = hora;
                                option.textContent = hora;
                                selectHora.appendChild(option);
                            }
                        });

                        // Seleccionar hora actual si existe
                        const horaActual = selectHora.getAttribute('data-hora-actual');
                        if (horaActual && !horasOcupadas.includes(horaActual)) {
                            selectHora.value = horaActual;
                        }
                    }

                    document.addEventListener('DOMContentLoaded', cargarHorasSegunDia);
                </script>

                <!-- Estado -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="pendiente" <?= $cita['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="confirmada" <?= $cita['estado'] === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                        <option value="cancelada" <?= $cita['estado'] === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        <option value="realizada" <?= $cita['estado'] === 'realizada' ? 'selected' : '' ?>>Realizada</option>
                    </select>
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