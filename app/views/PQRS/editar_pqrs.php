<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/PQRSController.php';

$controller = new PQRSController($db); // ✅ pasamos $db correctamente
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php?page=pqrs');
    exit();
}

// Obtener datos actuales del PQRS
$pqrs = $controller->ver($id);

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->editar(
        $id,
        $_POST['nombre'],
        $_POST['apellidos'],
        $_POST['email'],
        $_POST['tipo'],
        $_POST['mensaje'],
        $_POST['estado']
    );

    header('Location: index.php?page=pqrs');
    exit();
}
?>
<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">✏️ Editar PQRS</h1>
        <div class="card text-white mx-auto" style="max-width: 600px; padding: 40px;">
            <form action="index.php?page=editar_pqrs&id=<?= $pqrs['id_pqrs'] ?>" method="POST" class="text-white">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($pqrs['nombre']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" value="<?= htmlspecialchars($pqrs['apellidos']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($pqrs['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select" required>
                        <option value="Petición" <?= $pqrs['tipo'] === 'Petición' ? 'selected' : '' ?>>Petición</option>
                        <option value="Queja" <?= $pqrs['tipo'] === 'Queja' ? 'selected' : '' ?>>Queja</option>
                        <option value="Reclamo" <?= $pqrs['tipo'] === 'Reclamo' ? 'selected' : '' ?>>Reclamo</option>
                        <option value="Sugerencia" <?= $pqrs['tipo'] === 'Sugerencia' ? 'selected' : '' ?>>Sugerencia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea name="mensaje" id="mensaje" class="form-control" rows="4" required><?= htmlspecialchars($pqrs['mensaje']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="Pendiente" <?= $pqrs['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="En proceso" <?= $pqrs['estado'] === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                        <option value="Resuelto" <?= $pqrs['estado'] === 'Resuelto' ? 'selected' : '' ?>>Resuelto</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=pqrs" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-neon">Actualizar PQRS</button>
                </div>
            </form>
        </div>
    </div>
<main>