<?php
require_once __DIR__ . '/../../controllers/ClienteController.php';
$controller = new ClienteController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $controller->crearCliente($nombre, $apellido, $telefono, $correo);

    header("Location: ../../index.php?page=Cliente");
    exit;
}
?>
<body">
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">➕ Crear Cliente</h1>
        
        <div class="card shadow-lg mx-auto" style="max-width: 600px;">
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese el nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Ingrese el apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Ingrese el teléfono">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" id="correo" name="correo" class="form-control" placeholder="Ingrese el correo">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="../../index.php?page=home#contacto" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-neon">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<main>