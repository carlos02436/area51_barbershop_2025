<?php
$id = $_GET['id'];
?>
<section class="container py-5">
<h2>Cancelar Cita</h2>
<p>¿Seguro que deseas cancelar la cita #<?= $id ?>?</p>
<button id="btn-delete" class="btn btn-danger">Sí, cancelar</button>
<a href="index.php?page=panel" class="btn btn-secondary">Volver</a>
<script>
document.getElementById('btn-delete').addEventListener('click',function(){
    fetch('app/controllers/CitaController.php?action=cancelar',{
        method:'POST',
        body: JSON.stringify({id_cita: <?= $id ?>})
    }).then(res=>res.json())
      .then(resp=>{
          if(resp.success) location.href='index.php?page=panel';
          else alert('Error al cancelar');
      });
});
</script>
</section>