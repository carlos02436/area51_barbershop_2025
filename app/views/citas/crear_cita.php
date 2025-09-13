<?php
// Variables esperadas: $clientes, $barberos, $servicios
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Nueva Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">

            <form action="index.php?page=guardar_cita" method="POST">
                
                <!-- Cliente -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Cliente</label>
                    <select name="id_cliente" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($clientes as $clienteItem): ?>
                            <option value="<?= $clienteItem['id_cliente'] ?>">
                                <?= htmlspecialchars($clienteItem['nombre'] . ' ' . $clienteItem['apellido']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Barbero -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Barbero</label>
                    <select name="id_barbero" id="selectBarbero" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($barberos as $barberoItem): ?>
                            <option value="<?= $barberoItem['id_barbero'] ?>">
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
                                data-img="app/uploads/servicios/<?= htmlspecialchars($s['img_servicio']) ?>">
                                <?= htmlspecialchars($s['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imagen servicio -->
                <div class="mb-3 text-center" id="contenedorImgServicio" style="display:none;">
                    <img id="imgServicio" alt="Imagen Servicio" class="img-fluid rounded shadow" style="max-height:200px; width:auto; max-width:100%;">
                    <input type="hidden" name="img_servicio" id="inputImgServicio">
                </div>

                <script>
                    // Mostrar imagen servicio al seleccionar
                    document.getElementById('selectServicio').addEventListener('change', function() {
                        let img = this.options[this.selectedIndex].dataset.img;
                        if(img) {
                            document.getElementById('imgServicio').src = img;
                            document.getElementById('inputImgServicio').value = img.replace("app/uploads/servicios/","");
                            document.getElementById('contenedorImgServicio').style.display = 'block';
                        }
                    });
                </script>

                <!-- Fecha -->
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha_cita" id="fechaCita" class="form-control" required>
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
                        const diaSemana = new Date().getDay();

                        let horas = [];
                        let horasOcupadas = []; // <--- inicializamos vacío

                        if (diaSemana === 0) {
                            horas = generarHoras("09:00 am", "04:00 pm");
                        } else {
                            horas = generarHoras("08:00 am", "08:00 pm");
                        }

                        selectHora.innerHTML = '<option value="">Seleccionar...</option>';

                        horas.forEach(hora => {
                            if (!horasOcupadas.includes(hora)) {
                                const option = document.createElement('option');
                                option.value = hora;
                                option.textContent = hora;
                                selectHora.appendChild(option);
                            }
                        });

                        const horaActual = selectHora.getAttribute('data-hora-actual');
                        if (horaActual && !horasOcupadas.includes(horaActual)) {
                            selectHora.value = horaActual;
                        }
                    }

                        // Seleccionar hora actual si existe
                        const horaActual = selectHora.getAttribute('data-hora-actual');
                        if (horaActual && !horasOcupadas.includes(horaActual)) {
                            selectHora.value = horaActual;
                        }

                    document.addEventListener('DOMContentLoaded', cargarHorasSegunDia);
                </script>

                <?php
                $estadoActual = $cita['estado'] ?? 'pendiente'; // si $cita no existe, por defecto 'pendiente'
                ?>
                <div class="mb-3 w-100 mx-auto">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="pendiente" <?= $estadoActual === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="confirmada" <?= $estadoActual === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                        <option value="cancelada" <?= $estadoActual === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        <option value="realizada" <?= $estadoActual === 'realizada' ? 'selected' : '' ?>>Realizada</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between w-100 mx-auto">
                    <a href="index.php?page=home#contacto" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Crear Cita</button>
                </div>
            </form>
        </div>
    </div>
<main>