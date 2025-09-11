<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/administrador.php';
require_once __DIR__ . '/../models/usuario.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php?page=login');
    exit;
}
?>
<body>
    <div class="container mt-5">
        <h2 class="text-white mb-4">Gestión de Administradores</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['usuario'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td>
                        <a href="editar_admin.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="eliminar_admin.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<main>