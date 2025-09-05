<?php
require_once __DIR__ . '/../../config/database.php'; // Archivo de conexión
session_start();
if (!isset($_SESSION['admin_logged_in'])) header('Location: index.php?page=login');

$sql = "SELECT id, nombre, duracion, precio FROM servicios ORDER BY nombre";
$result = $conn->query($sql);
?>
<body>
    <div class="container mt-5">
        <h2 class="text-white mb-4">Gestión de Servicios</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['duracion'] ?> min</td>
                    <td>$<?= $row['precio'] ?></td>
                    <td>
                        <a href="editar_servicio.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="eliminar_servicio.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<main>