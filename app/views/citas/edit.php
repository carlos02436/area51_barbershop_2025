<?php
$id = $_GET['id'];
$citas = $citaModel->getCitas();
$cita = null;
foreach($citas as $c){ if($c['id_cita']==$id) $cita=$c; }
if(!$cita){ echo "Cita no encontrada"; exit; }
?>
<section class="container py-5">
<h2>Editar Cita</h2>
<form id="form-edit">
    <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
    <input type="number" name="id_cliente" value="<?= $cita['id_cliente'] ?>" required>
    <input type="number" name="id_barbero" value="<?= $cita['id_barbero'] ?>" required>
    <input type="number" name="id_servicio" value="<?= $cita['id_servicio'] ?>" required>
    <input type="date" name="fecha_cita" value="<?= $cita['fecha_cita'] ?>" required>
    <input type="time" name="hora_cita" value="<?= $cita['hora_cita'] ?>" required>
    <select name="estado" required>
        <option value="pendiente" <?= $cita['estado']=='pendiente'?'selected':''?>>Pendiente</option>
        <option value="confirmada" <?= $cita['estado']=='confirmada'?'selected':''?>>Confirmada</option>
        <option value="cancelada" <?= $cita['estado']=='cancelada'?'selected':''?>>Cancelada</option>
        <option value="realizada" <?= $cita['estado']=='realizada'?'selected':''?>>Realizada</option>
    </select>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>
<script>
document.getElementById('form-edit').addEventListener('submit', function(e){
    e.preventDefault();
    const data = Object.fromEntries(new FormData(this));
    fetch('app/controllers/CitaController.php?action=editar',{
        method:'POST',
        body: JSON.stringify(data)
    }).then(res=>res.json())
      .then(resp=>{
          if(resp.success) location.href='index.php?page=panel';
          else alert('Error al editar la cita');
      });
});
</script>
</section>