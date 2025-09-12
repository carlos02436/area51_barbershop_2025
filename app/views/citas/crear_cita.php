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
                    <select name="hora_cita" id="selectHora" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php
                        $barbero_id = $cita['barbero_id'] ?? null;
                        $fecha = $cita['fecha_cita'] ?? null;

                        // Horario de apertura y cierre
                        $inicio = new DateTime("08:00");
                        $fin = new DateTime("21:00");
                        $intervalo = new DateInterval("PT1H"); // intervalos de 1 hora

                        // Traer horas ocupadas en la fecha y barbero seleccionados
                        $horas_ocupadas = [];
                        $citas_dia = 0;

                        if ($fecha && $barbero_id) {
                            $stmt = $this->db->prepare("SELECT hora_cita FROM citas WHERE fecha_cita = ? AND barbero_id = ?");
                            $stmt->execute([$fecha, $barbero_id]);
                            $horas_ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

                            $stmt2 = $this->db->prepare("SELECT COUNT(*) FROM citas WHERE fecha_cita = ? AND barbero_id = ?");
                            $stmt2->execute([$fecha, $barbero_id]);
                            $citas_dia = $stmt2->fetchColumn();
                        }

                        for ($hora = clone $inicio; $hora <= $fin; $hora->add($intervalo)) {
                            $h = $hora->format("H:i:s");
                            $texto = $hora->format("h:i A");

                            $ocupada = in_array($h, $horas_ocupadas);
                            $disabled = false;

                            if ($barbero_id == 1) {
                                // Bloquear almuerzo
                                if ($hora >= new DateTime("12:00") && $hora < new DateTime("14:00")) {
                                    $disabled = true;
                                }
                                // Límite de 8 personas → deshabilitar todas las horas
                                if ($citas_dia >= 8) {
                                    $disabled = true;
                                }
                            }

                            if ($ocupada) {
                                $disabled = true;
                            }

                            echo '<option value="'.$h.'" '.($disabled ? "disabled" : "").'>'.$texto.($disabled ? " (ocupada)" : "").'</option>';
                        }
                        ?>
                    </select>
                </div>

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

    // ====================== REFERENCIAS ======================
    const selectBarbero = document.getElementById('selectBarbero');
    const inputFecha   = document.getElementById('inputFecha');
    const selectHora   = document.getElementById('selectHora');

    // ====================== HORAS ======================
    async function actualizarHoras(){
        const barbero = selectBarbero.value;
        const fecha = inputFecha.value;

        if(!barbero || !fecha) return;

        const res = await fetch(`index.php?page=horas_disponibles&id_barbero=${barbero}&fecha=${fecha}`);
        const horasDisponibles = await res.json();

        selectHora.innerHTML = '';
        if(horasDisponibles.length === 0){
            selectHora.disabled = true;
            selectHora.innerHTML = `<option value="">No hay horas disponibles</option>`;
            return;
        }

        // Horario de trabajo
        let horasTodas = barbero == 1 
            ? ["08:00","09:00","10:00","11:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00"]
            : ["08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00"];

        // Construir el select
        let optionDefault = document.createElement('option');
        optionDefault.value = "";
        optionDefault.textContent = "Seleccionar...";
        selectHora.appendChild(optionDefault);

        horasTodas.forEach(h => {
            let option = document.createElement('option');
            option.value = h;
            option.textContent = h;
            if(!horasDisponibles.includes(h)){
                option.disabled = true;
                option.textContent += " (ocupada)";
            }
            selectHora.appendChild(option);
        });

        selectHora.disabled = false;
    }

    // ====================== EVENTOS ======================
    selectBarbero.addEventListener('change', actualizarHoras);
    inputFecha.addEventListener('change', actualizarHoras);
</script>
<main>