<h2>Barberos</h2>
<a href="?controller=barberos&action=create" class="btn btn-primary">Nuevo Barbero</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th><th>Nombre</th><th>Especialidad</th><th>Teléfono</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($b = $barberos->fetch_assoc()): ?>
            <tr>
                <td><?= $b['id_barbero'] ?></td>
                <td><?= $b['nombre'] ?></td>
                <td><?= $b['especialidad'] ?></td>
                <td><?= $b['telefono'] ?></td>
                <td>
                    <a href="?controller=barberos&action=edit&id=<?= $b['id_barbero'] ?>">Editar</a> | 
                    <a href="?controller=barberos&action=destroy&id=<?= $b['id_barbero'] ?>">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>