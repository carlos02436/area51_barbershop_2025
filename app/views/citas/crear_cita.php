<?php
// Variables esperadas: $clientes, $barberos, $servicios
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Nueva Cita</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">

            <form id="formCita" method="POST" action="index.php?page=guardar_cita" class="text-white p-4 rounded shadow-sm">

                <!-- Verificación de Cliente -->
                <div class="mb-3">
                    <label class="form-label">Verificar Cliente (Email)</label>
                    <input type="email" id="verificarCliente" class="form-control" placeholder="Ingrese su email">
                    <small id="clienteMsg" class="text-warning"></small>
                </div>

                <!-- Selección Cliente -->
                <div class="mb-3">
                    <label class="form-label">Cliente</label>
                    <select name="id_cliente" id="selectCliente" class="form-select w-100">
                        <option value="">-- Seleccione Cliente --</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id_cliente'] ?>"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Registro rápido si no existe -->
                <div id="registroCliente" style="display:none;" class="mb-3">
                    <h5>Registrar Nuevo Cliente</h5>
                    <input type="text" id="nombreNuevo" placeholder="Nombre" class="form-control mb-2" required>
                    <input type="text" id="apellidoNuevo" placeholder="Apellido" class="form-control mb-2" required>
                    <input type="email" id="emailNuevo" placeholder="Email" class="form-control mb-2" required>
                    <button type="button" id="guardarCliente" class="btn btn-neon w-100">Registrar y Continuar</button>
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
                    <select name="id_servicio" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($servicios as $s): ?>
                            <option value="<?= htmlspecialchars($s['id_servicio']) ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

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
                    <a href="index.php?page=home#contacto" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Guardar Cita</button>
                </div>

            </form>
        </div>
    </div>

    <script>
    // ====================== CLIENTE ======================
    document.getElementById('verificarCliente').addEventListener('blur', function() {
        let email = this.value.trim();
        let msg = document.getElementById('clienteMsg');
        let selectCliente = document.getElementById('selectCliente');
        let registroDiv = document.getElementById('registroCliente');

        if(email === '') return;

        fetch('index.php?page=verificar_cliente&email=' + encodeURIComponent(email))
        .then(res => res.json())
        .then(data => {
            if(data.exists){
                msg.textContent = "Cliente encontrado: " + data.nombre;
                selectCliente.value = data.id_cliente;
                registroDiv.style.display = 'none';
            } else {
                msg.textContent = "Cliente no registrado. Complete el registro.";
                selectCliente.value = "";
                registroDiv.style.display = 'block';
                document.getElementById('emailNuevo').value = email;
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

    // ====================== HORAS ======================
    async function actualizarHoras(){
        const barbero = selectBarbero.value;
        const fecha = inputFecha.value;
        console.log("Barbero:", barbero, "Fecha:", fecha); // depuración
        if(!barbero || !fecha) return;

        const res = await fetch(`index.php?page=horas_disponibles&id_barbero=${barbero}&fecha=${fecha}`);
        const horasDisponibles = await res.json();
        console.log("Horas disponibles:", horasDisponibles); // depuración

        selectHora.innerHTML = '';
        if(horasDisponibles.length === 0){
            selectHora.disabled = true;
            selectHora.innerHTML = `<option value="">No hay horas disponibles</option>`;
            return;
        }

        let horasTodas = barbero == 1 
            ? ["08:00","09:00","10:00","11:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00"]
            : ["08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00"];

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
    </script>
<main>