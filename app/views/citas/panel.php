<?php
// NO incluir header/footer aquí porque ya lo hace index.php
$citas = $citaModel->getCitasConNombres(); // Este método debe devolver cliente, barbero y servicio como nombres
?>
<section class="container py-5 " style="scroll-margin-top:80px;margin: 10px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="text-center mb-4">Panel de Citas</h2>
            <div class="d-flex justify-content-between mb-3">
                <a href="index.php?page=create" class="btn btn-success">Nueva Cita</a>
                <a href="#contacto" class="btn btn-secondary">Volver al Contacto</a>
            </div>

            <div class="table-responsive shadow-sm rounded">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark rounded-top">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Barbero</th>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($citas)): ?>
                            <?php foreach($citas as $c): ?>
                            <tr>
                                <td><?= $c['id_cita'] ?></td>
                                <td><?= htmlspecialchars($c['nombre_cliente']) ?></td>
                                <td><?= htmlspecialchars($c['nombre_barbero']) ?></td>
                                <td><?= htmlspecialchars($c['nombre_servicio']) ?></td>
                                <td><?= $c['fecha_cita'] ?></td>
                                <td><?= $c['hora_cita'] ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $c['estado'] === 'pendiente' ? 'bg-warning text-dark' : '' ?>
                                        <?= $c['estado'] === 'confirmada' ? 'bg-primary' : '' ?>
                                        <?= $c['estado'] === 'realizada' ? 'bg-success' : '' ?>
                                        <?= $c['estado'] === 'cancelada' ? 'bg-danger' : '' ?>
                                    ">
                                        <?= ucfirst($c['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?page=edit&id=<?= $c['id_cita'] ?>" class="btn btn-primary btn-sm">Editar</a>
                                    <a href="index.php?page=delete&id=<?= $c['id_cita'] ?>" class="btn btn-danger btn-sm">Cancelar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay citas registradas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>