<?php
require_once __DIR__ . '/../../models/Barbero.php';
require_once __DIR__ . '/../../models/Servicio.php';

// Instanciar modelos
$barberoModel = new Barbero();
$servicioModel = new Servicio();

$barberos = $barberoModel->obtenerBarberos();
$servicios = $servicioModel->obtenerServicios();
?>

<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow rounded-4 p-4">
                <h2 class="text-center mb-4">¡Haz tu Reserva!</h2>
                <form id="form-create">
                    <div class="mb-3">
                        <input type="text" name="nombre" placeholder="Nombre" required class="form-control rounded-pill">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="apellido" placeholder="Apellido" required class="form-control rounded-pill">
                    </div>
                    <div class="mb-3">
                        <select name="id_barbero" class="form-select rounded-pill" required>
                            <option value="">Selecciona un barbero</option>
                            <?php foreach($barberos as $b): ?>
                                <option value="<?= $b['id_barbero'] ?>"><?= $b['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select name="id_servicio" class="form-select rounded-pill" required>
                            <option value="">Selecciona un servicio</option>
                            <?php foreach($servicios as $s): ?>
                                <option value="<?= $s['id_servicio'] ?>"><?= $s['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="date" name="fecha_cita" required class="form-control rounded-pill">
                    </div>
                    <div class="mb-3">
                        <input type="time" name="hora_cita" required class="form-control rounded-pill">
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-success rounded-pill px-4">Reservar</button>
                        <a href="index.php#contacto" class="btn btn-secondary rounded-pill px-4">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('form-create').addEventListener('submit', function(e){
    e.preventDefault();
    const data = Object.fromEntries(new FormData(this));
    fetch('app/controllers/CitaController.php?action=crear',{
        method:'POST',
        body: JSON.stringify(data)
    })
    .then(res=>res.json())
    .then(resp=>{
        if(resp.success) {
            alert('Cita reservada correctamente!');
            location.href='index.php?page=panel';
        } else {
            alert('Error: cita duplicada o límite alcanzado');
        }
    });
});
</script>