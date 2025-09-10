<?php
require_once __DIR__ . '/../../controllers/BarberoController.php';
$controller = new BarberoController();
$id = $_GET['id'] ?? null;
$barbero = $controller->mostrar($id);
?>
<body>
    <div class="container py-5" style="margin-top:80px;">
        <h2 class="fw-bold text-white mb-4 text-center">✂️ Editar Barbero</h2>

        <form method="POST" 
              action="index.php?page=actualizar_barbero&id=<?= $id ?>" 
              enctype="multipart/form-data"
              class="text-white p-4 rounded shadow-sm mx-auto" 
              style="max-width: 650px;border: 2px solid #28a745;">

            <!-- Nombre -->
            <div class="mb-3">
                <label class="form-label fw-bold">Nombre</label>
                <input type="text" name="nombre" value="<?= $barbero['nombre'] ?>" class="form-control border-success" required>
            </div>

            <!-- Especialidad -->
            <div class="mb-3">
                <label class="form-label fw-bold">Especialidad</label>
                <textarea name="especialidad" class="form-control border-success" rows="3" required><?= $barbero['especialidad'] ?></textarea>
            </div>

            <!-- Teléfono -->
            <div class="mb-3">
                <label class="form-label fw-bold">Teléfono</label>
                <input type="text" name="telefono" value="<?= $barbero['telefono'] ?>" class="form-control border-success" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" value="<?= $barbero['email'] ?>" class="form-control border-success" required>
            </div>

            <!-- Fecha Contratación -->
            <div class="mb-3">
                <label class="form-label fw-bold">Fecha de Contratación</label>
                <input type="date" name="fecha_contratacion" value="<?= $barbero['fecha_contratacion'] ?>" class="form-control border-success" required>
            </div>

            <!-- Imagen -->
            <div class="mb-3">
                <label class="form-label fw-bold">Imagen</label>
                <input type="file" name="img_barberos" class="form-control border-success">
                
                <?php if (!empty($barbero['img_barberos'])): ?>
                    <div class="mt-3 text-center">
                        <p class="text-light small mb-2">Imagen actual:</p>
                        <img src="app/uploads/barberos/<?= htmlspecialchars($barbero['img_barberos']) ?>" 
                             alt="Imagen del Barbero" 
                             class="img-thumbnail border-success"
                             style="max-width: 180px; border: 2px solid #28a745;">
                    </div>
                <?php else: ?>
                    <p class="text-warning small mt-2">⚠️ No hay imagen registrada.</p>
                <?php endif; ?>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <a href="index.php?page=barberos" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-neon">Actualizar</button>
            </div>
        </form>
    </div>
<main>
