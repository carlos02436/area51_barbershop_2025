<?php
require_once __DIR__ . '/../../controllers/ClienteController.php';
$controller = new ClienteController();

// Verificar si se pasó un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /area51_barbershop_2025/index.php?page=Cliente");
    exit;
}

$id = (int)$_GET['id'];

// Obtener cliente actual
$cliente = $controller->obtenerClientePorId($id);

// Si no existe cliente
if (!$cliente) {
    header("Location: /area51_barbershop_2025/index.php?page=Cliente");
    exit;
}

// Guardar cambios al enviar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo   = $_POST['correo'];

    $controller->actualizarCliente($id, $nombre, $apellido, $telefono, $correo);

    // ✅ Ruta absoluta dentro del proyecto
    header("Location: /area51_barbershop_2025/index.php?page=Cliente");
    exit;
}

?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar Cliente</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <form method="POST">
                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" 
                           value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
                </div>

                <!-- Apellido -->
                <div class="mb-3">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" 
                           value="<?= htmlspecialchars($cliente['apellido']) ?>" required>
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" 
                           value="<?= htmlspecialchars($cliente['telefono']) ?>">
                </div>

                <!-- Correo -->
                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control" 
                           value="<?= htmlspecialchars($cliente['correo']) ?>">
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=Cliente" class="btn btn-danger" style="width:100px;">Cancelar</a>
                    <button type="submit" class="btn btn-neon" style="width:100px;">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
<main>