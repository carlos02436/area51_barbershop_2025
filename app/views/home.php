<body>
    <!-- Encabezado -->
    <header id="inicio" class="hero" style="z-index: -1;border-radius: 40% 10% 60%/20% 80% 20%;">
        <div class="hero-background"></div>
        <div class="container">
            <h1 class="fs-1">Área 51_Barber Shop</h1>
            <p class="lead">Descubre tu Mejor Estilo</p>
        </div>
    </header>

    <!-- sección nosotros -->
    <section id="nosotros" class="py-5 fade-in-section gx-10">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Nosotros</h2>

            <div transition-style class="--in-custom">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-justify">
                        <div class="text-center">
                            <img src="app/uploads/servicios/yeison3.png" alt="Barbero Yeison en Área 51"
                                class="img-fluid about-us-image">
                        </div>

                        <p class="mt-4 text-justify">
                            En <strong>ÁREA 51_BARBER SHOP</strong> nos apasiona transformar tu estilo.
                            Combinamos lo clásico con lo moderno para ofrecerte una experiencia única.
                            Nuestra historia comenzó de forma humilde, atendiendo en las calles, con el sueño de llegar
                            más lejos.
                            Gracias al esfuerzo, la dedicación y la ayuda de Dios, hoy contamos con nuestro propio local
                            y una distinguida clientela que confía en nuestro talento y pasión.</p>

                        <p class="mt-4 text-justify">
                            Creemos en el poder de servir a nuestra comunidad, y por eso, cada año realizamos la
                            <strong>MOTILATÓN</strong>, una jornada social donde compartimos nuestro arte al servicio de
                            quienes más lo necesitan.
                            <strong>ÁREA 51_BARBER SHOP</strong>, más que una barbería, una familia que crece contigo.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Misión y Visión -->
            <div class="row justify-content-center mt-5 gx-10">
                <!-- Misión -->
                <div class="col-lg-5 col-md-6 mb-4 d-flex flex-column align-items-center">
                    <h2 class="section-title mb-4">Misión</h2>
                    <div class="card h-100 w-100">
                        <div class="card-body">
                            <div class="mb-3" style="height: 200px; overflow: hidden; border-radius: 20px;">
                                <img src="public/img/grupoArea51.jpg" alt="Misión" class="img-fluid w-100 h-100"
                                    style="object-fit: cover; border-radius: 20px;">
                            </div>
                            <p class="card-text text-justify text-white">
                                Ofrecer servicios de barbería con altos estándares de calidad en un ambiente innovador y
                                estilizado,
                                resaltando la personalidad de cada cliente. Nos enfocamos en proporcionar una
                                experiencia
                                única y personalizada, donde cada corte y estilo refleje la esencia de quienes nos
                                eligen,
                                asegurando su satisfacción y confianza en cada visita.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Visión -->
                <div class="col-lg-5 col-md-6 mb-4 d-flex flex-column align-items-center">
                    <h2 class="section-title mb-4">Visión</h2>
                    <div class="card h-100 w-100">
                        <div class="card-body">
                            <div class="mb-3" style="height: 200px; overflow: hidden; border-radius: 20px;">
                                <img src="public/img/yeison4.png" alt="Visión" class="img-fluid w-100 h-100"
                                    style="object-fit: cover; border-radius: 20px;">
                            </div>
                            <p class="card-text text-justify text-white">
                                Ser reconocidos como la barbería más innovadora y auténtica de la ciudad, destacándonos
                                por
                                nuestro profesionalismo y creatividad. Queremos ser el referente de estilo y vanguardia,
                                brindando
                                no solo cortes de calidad, sino también una experiencia única que transforme la
                                percepción de la
                                barbería en la comunidad.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios -->
    <?php
    // Incluimos el controlador usando ruta correcta
    require_once __DIR__ . '/../controllers/ServicioController.php';

    // Instanciamos el controlador y obtenemos los servicios
    $controller = new ServicioController();
    $servicios = $controller->listarServicios();
    ?>

    <!-- Sección Servicios -->
    <section id="servicios" class="py-5 fade-in-section">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Nuestros Servicios</h2>
            <div class="row">
                <?php foreach ($servicios as $servicio) { ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 bg-transparent border-0">
                            <div class="card-body text-white"
                                style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">

                                <!-- Imagen -->
                                <div style="width: 100%; height: 200px; overflow: hidden; display: flex; 
                                            align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 20px;">
                                    <img src="app/uploads/servicios/<?= htmlspecialchars($servicio['img_servicio']) ?>"
                                        alt="<?= htmlspecialchars($servicio['nombre']) ?>"
                                        style="width: 97%; height: 97%; object-fit: cover; border-radius: 20px;">
                                </div>

                                <!-- Nombre -->
                                <h5 class="card-title">
                                    <i class="fas fa-cut me-2"></i><?= htmlspecialchars($servicio['nombre']) ?>
                                </h5>

                                <!-- Descripción -->
                                <p class="card-text"><?= htmlspecialchars($servicio['descripcion']) ?></p>

                                <!-- Precio -->
                                <?php if (!empty($servicio['precio'])) { ?>
                                    <p class="card-text"><strong>Precio: $<?= number_format($servicio['precio'], 0, ',', '.') ?></strong></p>
                                <?php } ?>

                                <!-- Observación -->
                                <?php if (!empty($servicio['observacion'])) { ?>
                                    <p class="card-text"><?= htmlspecialchars($servicio['observacion']) ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Sección Barberos -->
    <?php
    require_once __DIR__ . '/../controllers/BarberoController.php';

    $controller = new BarberoController();
    $barberos = $controller->listar(); 
    ?>

    <section id="barberos" class="py-5 fade-in-section">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Sección de Barberos</h2>
            <div class="container">

                <?php foreach ($barberos as $index => $barbero): ?>
                    <div class="row align-items-center mb-5 <?= ($index % 2 != 0) ? 'flex-md-row-reverse' : '' ?>">
                        <div class="col-md-6">
                            <img src="app/uploads/barberos/<?= htmlspecialchars($barbero['img_barberos']) ?>"
                                alt="<?= htmlspecialchars($barbero['nombre']) ?>"
                                style="border-radius: 20px; width: 70%; height: 70%;">
                        </div>
                        <div class="col-md-6">
                            <h2 class="text-center mb-4 section-title"><?= htmlspecialchars($barbero['nombre']) ?></h2>
                            <p class="card-text text-justify">
                                <?= htmlspecialchars($barbero['especialidad']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Galería de Fotos -->
    <?php
    require_once __DIR__ . '/../controllers/GaleriaController.php';
    require_once __DIR__ . '/../controllers/VideoController.php';

    $galeriaController = new GaleriaController();
    $imagenes = $galeriaController->listarImagenes(9);

    $videoController = new VideoController($db);
    $videos = $videoController->index(); // obtiene 3 videos por defecto
    ?>

    <!-- Sección Galería -->
    <section id="galeria" class="py-5 fade-in-section">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Galería de estilos</h2>
            <div class="row">
                <?php foreach ($imagenes as $img): ?>
                    <div class="col-md-4 col-6 mb-4">
                        <img src="app/uploads/servicios/<?= htmlspecialchars($img['img_galeria']) ?>"
                            alt="<?= htmlspecialchars($img['nombre_galeria']) ?>"
                            class="img-fluid"
                            style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Sección Videos -->
    <section id="videos" class="py-5 fade-in-section">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Videos y algo más...</h2>
            <div class="row g-4" id="video-container">
                <?php foreach ($videos as $row):
                    // Extraer ID del video para embed
                    preg_match('/[\\?&]v=([^&#]+)/', $row['link'], $matches);
                    $videoId = isset($matches[1]) ? $matches[1] : basename(parse_url($row['link'], PHP_URL_PATH));
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="embed-responsive embed-responsive-16by9 video-wrapper">
                            <iframe class="embed-responsive-item"
                                src="https://www.youtube.com/embed/<?= htmlspecialchars($videoId) ?>"
                                allowfullscreen></iframe>
                        </div>
                        <h5 class="mt-2 text-center"><?= htmlspecialchars($row['titulo']) ?></h5>
                    </div>
                <?php endforeach; ?>

                <!-- TikTok Videos -->
                <?php
                require_once __DIR__ . '/../controllers/TikTokController.php';

                $tiktokController = new TikTokController($db);
                $tiktoks = $tiktokController->listarVideos(3);
                ?>

                <!-- Sección TikTok -->
                <div class="container py-4">
                    <div class="row">
                        <?php foreach ($tiktoks as $video): ?>
                            <div class="col-md-6 col-lg-4 d-flex justify-content-center mb-4">
                                <blockquote class="tiktok-embed iframe"
                                    cite="<?= htmlspecialchars($video['url']) ?>"
                                    data-video-id="<?= htmlspecialchars($video['video_id']) ?>">
                                    <section></section>
                                </blockquote>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
    </section>

    <!-- Scripts de TikTok -->
    <script async src="https://www.tiktok.com/embed.js"></script>

    <!-- Noticias -->
    <?php
    require_once __DIR__ . '/../controllers/NoticiasController.php';

    $noticiasController = new NoticiasController($db);
    $noticias = $noticiasController->listarNoticias(); // Trae las últimas 3 noticias
    ?>

    <section id="noticias" class="py-5 fade-in-section">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Noticias y Eventos</h2>
            <div class="row g-4" id="video-container">

            <?php foreach ($noticias as $noticia): ?>
                <div class="col-md-6 col-lg-4 d-flex justify-content-center mb-4">
                    <div class="card w-100 text-white" 
                     style="background: rgba(0,0,0,0.6); border: none; 
                            backdrop-filter: blur(6px); 
                            box-shadow: 0 4px 12px rgba(0,0,0,0.4); 
                            border-radius: 12px;">
                        <div class="card-body">
                            <h5 class="card-title text-white"><?= htmlspecialchars($noticia['titulo']) ?></h5>
                            <p class="card-text text-light">
                                <?= nl2br(htmlspecialchars(substr($noticia['contenido'], 0, 120))) ?>...
                            </p>
                            <small class="text-muted" style="color: #bbb !important;">
                                Fecha: <?= date('d/m/Y H:i', strtotime($noticia['fecha_publicacion'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php
    require_once __DIR__ . '/../controllers/TestimoniosController.php';

    $testimonioController = new TestimoniosController();
    $testimonios = $testimonioController->listarTestimonios();
    ?>

    <!-- Testimonios -->
    <section id="testimonios" class="py-5 fade-in-section"
        style="padding-top: 150px; padding-bottom: 150px; margin: auto;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Testimonios</h2>

            <!-- Quité el autoplay (data-bs-ride="carousel") -->
            <div id="testimonialCarousel" class="carousel slide">
                <div class="carousel-inner h-100" style="min-height: 250px;"> <!-- altura fija -->
                    <?php foreach ($testimonios as $index => $t): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?> h-100">
                            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                <img src="app/uploads/testimonios/<?= htmlspecialchars($t['img']) ?>"
                                    alt="<?= htmlspecialchars($t['nombre']) ?>"
                                    class="rounded-circle mb-4"
                                    style="width: 90px; height: 90px; object-fit: cover;">
                                <p class="text-center mb-4"><?= htmlspecialchars($t['mensaje']) ?></p>
                                <h5><?= htmlspecialchars($t['nombre']) ?></h5>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Controles (flechas) -->
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="prev" style="border: none; background: transparent;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"
                        style="text-shadow: 0 0 10px #00ff00, 0 0 20px #00ff00, 0 0 30px #00ff00;"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="next" style="border: none; background: transparent;">
                    <span class="carousel-control-next-icon" aria-hidden="true"
                        style="text-shadow: 0 0 10px #00ff00, 0 0 20px #00ff00, 0 0 30px #00ff00;"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Obtén el carrusel
        var testimonialCarousel = document.querySelector('#testimonialCarousel');
        var carousel = new bootstrap.Carousel(testimonialCarousel, {
            interval: 5000,   // tiempo en milisegundos (5 segundos)
            ride: "carousel"  // activa el autoplay manualmente
        });
    });
    </script>

    <!-- Contacto -->
    <section id="contactanos" class="py-5 fade-in-section contacto-section" style="background:transparent;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Contacto</h2>

            <div class="row">
                <!-- Información de Contacto -->
                <div class="col-12 col-lg-6 mb-4">
                    <h4 class="mt-4"><i class="fas fa-info-circle me-2"></i>Información de Contacto</h4>
                    <p><strong>Dirección:</strong> Transversal 2 # 04 - 01 Barrio 17 de Febrero</p>
                    <p><strong>Teléfono:</strong> (+57) 312 473 22 36</p>
                    <p><strong>Email:</strong> area51barbershop2025@gmail.com</p>

                    <h4 class="mt-4"><i class="far fa-clock me-2"></i>Horario de Atención</h4>
                    <p>Lunes a sábado: 08:00 AM - 08:00 PM</p>
                    <p>Domingos: 09:00 AM - 4:00 PM</p>

                    <h4 class="mt-4"><i class="fas fa-share-alt me-2"></i>Síguenos</h4>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/share/1AYqvPUhT9/?mibextid=wwXIfr" aria-label="Facebook" target="_blank"><i class="fab fa-facebook-f mt-3"></i></a>
                        <a href="http://wa.me/573124732236" aria-label="WhatsApp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://www.instagram.com/area51barbershop_lajagua/" aria-label="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.tiktok.com/@ysarmiento.barber" aria-label="TikTok" target="_blank"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Información y Enlace de Reservas -->
                <div class="col-12 col-lg-6 mb-3">
                    <h4 class="mt-4"><i class="far fa-calendar-alt me-2"></i>Reserva tu Cita</h4>
                    <div>
                        <p>
                            En <strong>Área 51_Barber Shop</strong>, tu estilo es nuestra prioridad.
                            Nuestro equipo de barberos expertos está listo para ofrecerte una experiencia de otro nivel.
                            Ya sea que busques un corte clásico, un diseño moderno o nuestro exclusivo
                            <em><strong>Paquete Premium</strong></em>, estamos aquí para ti.
                        </p>
                        <p>
                            ¡No esperes más! Agenda tu cita con nosotros y prepárate para salir renovado.
                            Ponte en contacto a través de nuestro WhatsApp, redes sociales o visítanos directamente.
                            ¡Solamente registrate y haz que tu próxima transformación con estilo
                            comience ahora...!
                        </p>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a class="btn btn-dark" href="index.php?page=crear_cliente">Registrarse</a>
                        <a class="btn btn-neon" href="index.php?page=crear_cita">Reservar</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<main>