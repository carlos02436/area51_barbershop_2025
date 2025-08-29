<!-- app/views/barberos/index.php -->
<section id="barberos" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
    <div class="container">
        <h2 class="text-center mb-5 section-title">Sección de Barberos</h2>
        <div class="container">

            <?php foreach ($barberos as $index => $barbero): ?>
                <div class="row align-items-center mb-5 <?= ($index % 2 != 0) ? 'flex-md-row-reverse' : '' ?>">
                    
                    <!-- Imagen -->
                    <div class="col-md-6">
                        <img src="public/img/<?= htmlspecialchars($barbero['img_barberos'] ?? 'default.png') ?>"
                             alt="<?= htmlspecialchars($barbero['nombre']) ?>"
                             style="border-radius: 20px; width: 70%; height: 70%;">
                    </div>
                    
                    <!-- Descripción -->
                    <div class="col-md-6">
                        <h2 class="text-center mb-4 section-title"><?= htmlspecialchars($barbero['nombre']) ?></h2>
                        <p class="card-text">
                            <?= htmlspecialchars($barbero['especialidad']) ?><br>
                            <small>Tel: <?= htmlspecialchars($barbero['telefono']) ?> </small><br>
                            <small>Contratado: <?= htmlspecialchars($barbero['fecha_contratacion']) ?> </small>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>
