<?php
// Variables esperadas: $clientes, $barberos, $servicios
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Nueva Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">

            <form action="index.php?page=guardar_cita" method="POST">
                <!-- Selección Cliente -->
                <div class="mb-3">
                    <label class="form-label">Cliente</label>
                    <select name="id_cliente" id="selectCliente" class="form-select w-100">
                        <option value="">Seleccionar...</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id_cliente'] ?>"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Barbero -->
                <div class="mb-3">
                    <label class="form-label">Barbero</label>
                    <select name="id_barbero" id="selectBarbero" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($barberos as $b): ?>
                            <option value="<?= $b['id_barbero'] ?>"><?= htmlspecialchars($b['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Servicio -->
                <div class="mb-3">
                    <label class="form-label">Servicio</label>
                    <select name="id_servicio" class="form-select" id="selectServicio" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($servicios as $s): ?>
                            <option value="<?= htmlspecialchars($s['id_servicio']) ?>"
                                    data-img="<?= htmlspecialchars($s['img_servicio']) ?>"
                                    <?php if (isset($cita) && $cita['id_servicio'] == $s['id_servicio']) echo 'selected'; ?>>
                                <?= htmlspecialchars($s['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imagen servicio -->
                <div class="mb-3 text-center" id="contenedorImgServicio" style="display:none;">
                    <img id="imgServicio" src="" alt="Imagen Servicio"
                        class="img-fluid rounded shadow"
                        style="max-width:250px; max-height:200px; width:auto; height:auto;">
                    <input type="hidden" name="img_servicio" id="inputImgServicio"
                        value="<?= isset($cita) ? htmlspecialchars($cita['img_servicio']) : '' ?>">
                </div>

                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const selectServicio = document.getElementById("selectServicio");
                    const contenedorImg = document.getElementById("contenedorImgServicio");
                    const imgServicio = document.getElementById("imgServicio");
                    const inputImgServicio = document.getElementById("inputImgServicio");

                    function mostrarImagen() {
                        const selectedOption = selectServicio.options[selectServicio.selectedIndex];
                        const imgSrc = selectedOption ? selectedOption.getAttribute("data-img") : "";

                        if (imgSrc) {
                            // 📌 Ajuste: ahora carga desde app/uploads/servicios/
                            imgServicio.src = "app/uploads/servicios/" + imgSrc;
                            inputImgServicio.value = imgSrc;
                            contenedorImg.style.display = "block";
                        } else {
                            imgServicio.src = "";
                            inputImgServicio.value = "";
                            contenedorImg.style.display = "none";
                        }
                    }

                    mostrarImagen(); // Para mostrar si ya está seleccionado (en editar)
                    selectServicio.addEventListener("change", mostrarImagen);
                });
                </script>

                <!-- Fecha -->
                <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha_cita" id="inputFecha" class="form-control" min="<?= date('Y-m-d') ?>" required>
                </div>

                <!-- Hora -->
                <div class="mb-3" id="horaContainer">
                    <label class="form-label">Hora</label>
                    <select name="hora_cita" id="selectHora" class="form-select" required
                        data-hora-actual="<?= $cita['hora_cita'] ?? '' ?>">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>

                <script>
                    // Función para generar horas en punto entre dos horas
                    function generarHorasEnPunto(inicio, fin) {
                        const horas = [];
                        let startHour = parseInt(inicio.split(':')[0]);
                        let endHour = parseInt(fin.split(':')[0]);
                        let startPeriod = inicio.toLowerCase().includes('pm') ? 'pm' : 'am';
                        let endPeriod = fin.toLowerCase().includes('pm') ? 'pm' : 'am';

                        if (startPeriod === 'pm' && startHour !== 12) startHour += 12;
                        if (endPeriod === 'pm' && endHour !== 12) endHour += 12;
                        if (endPeriod === 'am' && endHour === 12) endHour = 0;

                        for (let h = startHour; h <= endHour; h++) {
                            let hour12 = h % 12 === 0 ? 12 : h % 12;
                            let period = h >= 12 ? 'pm' : 'am';
                            horas.push(`${hour12.toString().padStart(2, '0')}:00 ${period}`);
                        }

                        return horas;
                    }

                    function actualizarHoras() {
                        const fechaInput = document.getElementById('fechaCita');
                        const selectHora = document.getElementById('selectHora');
                        const horaActual = selectHora.getAttribute('data-hora-actual');

                        const fechaSeleccionada = new Date(fechaInput.value);
                        const dia = fechaSeleccionada.getDay(); // 0 = domingo

                        let horas = [];

                        if (dia === 0) {
                            horas = generarHorasEnPunto("09:00 am", "04:00 pm");
                        } else {
                            horas = generarHorasEnPunto("08:00 am", "08:00 pm");
                        }

                        // Limpiar opciones previas
                        selectHora.innerHTML = '<option value="">Seleccionar...</option>';

                        // Agregar horas disponibles (que no están ocupadas)
                        horas.forEach(hora => {
                            if (!horasOcupadas.includes(hora.toLowerCase())) {
                                const option = document.createElement('option');
                                option.value = hora;
                                option.textContent = hora;
                                selectHora.appendChild(option);
                            }
                        });

                        // Seleccionar hora actual si está disponible
                        if (horaActual && !horasOcupadas.includes(horaActual.toLowerCase())) {
                            selectHora.value = horaActual;
                        }
                    }

                    // Evento al cambiar la fecha
                    document.getElementById('fechaCita').addEventListener('change', actualizarHoras);

                    // También puedes llamarla en DOMContentLoaded si ya hay una fecha seleccionada
                    document.addEventListener('DOMContentLoaded', () => {
                        const fechaInput = document.getElementById('fechaCita');
                        if (fechaInput.value) {
                            actualizarHoras();
                        }
                    });
                </script>

                <!-- Estado -->
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select w-100">
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="cancelada">Cancelada</option>
                        <option value="realizada">Realizada</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=home" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Guardar Cita</button>
                </div>

            </form>
        </div>
    </div>

    <script>
    // ====================== CLIENTE ======================
        document.querySelector('select[name="id_servicio"]').addEventListener('change', function(){
            let servicioId = this.value;
            if(!servicioId) return;

            fetch('index.php?page=obtener_img_servicio&id_servicio=' + servicioId)
            .then(res => res.json())
            .then(data => {
                if(data.img){
                    document.getElementById('contenedorImgServicio').style.display = 'block';
                    document.getElementById('imgServicio').src = data.img;
                    document.getElementById('inputImgServicio').value = data.img;
                } else {
                    document.getElementById('contenedorImgServicio').style.display = 'none';
                    document.getElementById('inputImgServicio').value = '';
                }
            });
        });

        // Guardar cliente nuevo
        document.getElementById('guardarCliente').addEventListener('click', function() {
            let nombre = document.getElementById('nombreNuevo').value.trim();
            let apellido = document.getElementById('apellidoNuevo').value.trim();
            let email = document.getElementById('emailNuevo').value.trim();

            if(nombre === '' || apellido === '' || email === ''){
                alert("Complete todos los campos del registro.");
                return;
            }

            fetch('index.php?page=guardar_cliente', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({nombre, apellido, email})
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert("Cliente registrado correctamente.");
                    document.getElementById('selectCliente').innerHTML += `<option value="${data.id_cliente}" selected>${nombre} ${apellido}</option>`;
                    document.getElementById('registroCliente').style.display = 'none';
                    document.getElementById('clienteMsg').textContent = "Cliente registrado: " + nombre + " " + apellido;
                } else {
                    alert("Error al registrar cliente.");
                }
            });
        });
    </script>
<main>