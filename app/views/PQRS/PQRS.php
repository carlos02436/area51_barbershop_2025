<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/PQRSController.php';

$pqrsController = new PQRSController($db);
$registros = $pqrsController->listar();
?>
<body>
    <h2>Gestión de PQRS</h2>
    <a href="crear_pqrs.php" class="btn btn-success mb-3">Crear nuevo</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Mensaje</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $row): ?>
                <tr>
                    <td><?= $row['id_pqrs'] ?></td>
                    <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellidos']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['tipo'] ?></td>
                    <td><?= nl2br(htmlspecialchars($row['mensaje'])) ?></td>
                    <td><?= $row['estado'] ?></td>
                    <td>
                        <a href="index.php?page=editar_pqrs&id=<?= $row['id_pqrs'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="index.php?page=eliminar_pqrs&id=<?= $row['id_pqrs'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Marcar como resuelto?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<main>