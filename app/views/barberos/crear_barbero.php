<body>
    <div class="container py-5" style="margin-top:100px;">
        <h1 class="fw-bold text-white mb-4 text-center">💈 Crear Nuevo Barbero</h1>

        <form action="index.php?page=guardar_barbero" method="POST" enctype="multipart/form-data" 
              class="text-white p-4 rounded shadow-sm mx-auto" 
              style="max-width: 600px; background: transparent; border: 2px solid #00ff99; box-shadow: 0 0 15px rgba(0,255,153,0.5);">
            
            <!-- NOMBRE -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control form-control-sm" placeholder="Nombre del barbero" required>
            </div>

            <!-- ESPECIALIDAD -->
            <div class="mb-3">
                <label for="especialidad" class="form-label">Especialidad</label>
                <textarea name="especialidad" id="especialidad" class="form-control form-control-sm" rows="2" placeholder="Especialidad del barbero" required></textarea>
            </div>

            <!-- TELÉFONO -->
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control form-control-sm" placeholder="Ej: 3001234567" required>
            </div>

            <!-- EMAIL -->
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control form-control-sm" placeholder="correo@ejemplo.com" required>
            </div>

            <!-- FECHA CONTRATACIÓN -->
            <div class="mb-3">
                <label for="fecha_contratacion" class="form-label">Fecha de contratación</label>
                <input type="date" name="fecha_contratacion" id="fecha_contratacion" class="form-control form-control-sm" required>
            </div>

            <!-- IMAGEN -->
            <div class="mb-3">
                <label for="img_barberos" class="form-label">Imagen del barbero</label>
                <input type="file" name="img_barberos" id="img_barberos" class="form-control form-control-sm">
            </div>

            <!-- BOTONES -->
            <div class="d-flex justify-content-between mt-4">
                <a href="index.php?page=barberos" class="btn btn-danger px-4">Cancelar</a>
                <button type="submit" class="btn btn-neon px-4">Guardar</button>
            </div>
        </form>
    </div>
<main>