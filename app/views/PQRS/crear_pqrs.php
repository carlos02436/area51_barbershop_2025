<?php
require_once __DIR__ . '/../auth_admin.php';
require_once __DIR__ . '/../../controllers/PQRSController.php';

$controller = new PQRSController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->crear($_POST['nombre'], $_POST['apellidos'], $_POST['email'], $_POST['tipo'], $_POST['mensaje']);
    header("Location: pqrs.php");
    exit;
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear PQRS</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 30px;">

            <form action="index.php?page=guardar_pqrs" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" class="form-control" required>
                        <option value="Petición">Petición</option>
                        <option value="Queja">Queja</option>
                        <option value="Reclamo">Reclamo</option>
                        <option value="Sugerencia">Sugerencia</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea name="mensaje" class="form-control" rows="4" required></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=home" class="btn btn-danger" style="width:130px;">Cancelar</a>
                    <button type="submit" class="btn btn-neon" style="width:130px;">Crear PQRS</button>
                </div>
            </form>
        </div>
    </div>
<main>