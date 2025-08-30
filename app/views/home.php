<body>

    <!-- sección nosotros -->
    <section id="nosotros" class="py-5 fade-in-section gx-10" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Nosotros</h2>

            <div transition-style class="--in-custom">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-justify">
                        <img src="public/img/yeison3.png" alt="Barbero Yeison en Área 51" class="img-fluid about-us-image">
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
    <section id="servicios" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
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
                                    <img src="public/img/<?= htmlspecialchars($servicio['img_servicio']) ?>" 
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
    $barberos = $controller->listarBarberos(5); // Solo 5 barberos
    ?>

    <section id="barberos" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Sección de Barberos</h2>
            <div class="container">

                <?php foreach ($barberos as $index => $barbero): ?>
                    <div class="row align-items-center mb-5 <?= ($index % 2 != 0) ? 'flex-md-row-reverse' : '' ?>">
                        <div class="col-md-6">
                            <img src="public/img/<?= htmlspecialchars($barbero['img_barberos']) ?>"
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

    $videoController = new VideoController();
    $videos = $videoController->listarVideos(3);
    ?>

    <!-- Sección Galería -->
    <section id="galeria" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Galería de estilos</h2>
            <div class="row">
                <?php foreach ($imagenes as $img): ?>
                    <div class="col-md-4 col-6 mb-4">
                        <img src="public/img/<?= htmlspecialchars($img['img_galeria']) ?>" 
                            alt="<?= htmlspecialchars($img['nombre_galeria']) ?>" 
                            class="img-fluid"
                            style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Sección Videos -->
    <section id="videos" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Videos y algo más...</h2>
            <div class="row g-4" id="video-container">
                <?php foreach ($videos as $row): 
                    // Extraer ID del video para embed
                    preg_match('/[\\?&]v=([^&#]+)/', $row['url'], $matches);
                    $videoId = isset($matches[1]) ? $matches[1] : basename(parse_url($row['url'], PHP_URL_PATH));
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

                $tiktokController = new TikTokController();
                $tiktoks = $tiktokController->listarVideos(10);
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

    <?php
    require_once __DIR__ . '/../controllers/NoticiaController.php';

    $noticiaController = new NoticiaController();
    $noticias = $noticiaController->listarNoticias(3);
    ?>

    <!-- Sección Noticias -->
    <?php
    require_once __DIR__ . '/../controllers/NoticiaController.php';

    $noticiaController = new NoticiaController();
    $noticias = $noticiaController->listarNoticias(3);
    ?>

    <!-- Sección Noticias -->
    <section id="noticias" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Noticias y Eventos</h2>
            <div class="row">
                <?php if (!empty($noticias)): ?>
                    <?php foreach ($noticias as $row): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 bg-transparent border-0">
                                <div class="card-body d-flex flex-column text-white"
                                    style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);">
                                    <h5 class="card-title">
                                        <i class="far fa-newspaper me-2"></i><?= htmlspecialchars($row['titulo']) ?>
                                    </h5>
                                    <p class="card-text text-justify"><?= htmlspecialchars($row['contenido']) ?></p>
                                    <p class="card-text mt-auto">
                                        <small>Publicado: <?= date("d-m-Y", strtotime($row['fecha_publicacion'])) ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-white">No hay noticias disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php
    require_once __DIR__ . '/../controllers/TestimonioController.php';

    $testimonioController = new TestimonioController();
    $testimonios = $testimonioController->listarTestimonios();
    ?>

    <!-- Testimonios -->
    <section id="testimonios" class="py-5 fade-in-section"
        style="padding-top: 150px; padding-bottom: 150px; margin: auto; scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Testimonios</h2>

            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" style="height: 200px;">

                    <?php foreach ($testimonios as $index => $t): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" style="margin: 15px auto;">
                            <div class="d-flex flex-column align-items-center">
                                <img src="public/img/<?= htmlspecialchars($t['img']) ?>" 
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

    <!-- Contacto -->
    <section id="contacto" class="py-5 fade-in-section mb-5 contacto-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Contacto</h2>

            <div class="row">
                <!-- Información de Contacto -->
                <div class="col-12 col-lg-6 mb-4">
                    <h4><i class="fas fa-info-circle me-2"></i>Información de Contacto</h4>
                    <p><strong>Dirección:</strong> Transversal 2 # 04 - 01 Barrio 17 de Febrero</p>
                    <p><strong>Teléfono:</strong> (+57) 312 473 22 36</p>
                    <p><strong>Email:</strong> area51barbershop2025@gmail.com</p>

                    <h4 class="mt-4"><i class="far fa-clock me-2"></i>Horario de Atención</h4>
                    <p>Lunes a sábado: 08:00 AM - 08:00 PM</p>
                    <p>Domingos: 09:00 AM - 4:00 PM</p>

                    <h4 class="mt-4"><i class="fas fa-share-alt me-2"></i>Síguenos</h4>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/share/1AYqvPUhT9/?mibextid=wwXIfr" target="_blank"
                            class="me-3">
                            <i class="fab fa-facebook fa-2x"></i>
                        </a>
                        <a href="https://www.instagram.com/area51barbershop_lajagua?igsh=MWxoMjBnb3I0azI2YQ%3D%3D&utm_source=qr"
                            target="_blank" class="me-3">
                            <i class="fab fa-instagram fa-2x"></i>
                        </a>
                        <a href="https://www.tiktok.com/@ysarmiento.barber?_t=ZS-8voHYlVk1Ym&_r=1" target="_blank"
                            class="me-3">
                            <i class="fab fa-tiktok fa-2x"></i>
                        </a>
                        <a href="https://whatsapp.com" target="_blank">
                            <i class="fab fa-whatsapp fa-2x"></i>
                        </a>
                    </div>
                </div>

                <!-- Información y Enlace de Reservas -->
                <div class="col-12 col-lg-6 mb-4">
                    <h4><i class="far fa-calendar-alt me-2"></i>Reserva tu Cita</h4>
                    <div class="p-3">
                        <p style="font-size: 1.1rem;">
                            En <strong>Área 51_Barber Shop</strong>, tu estilo es nuestra prioridad.
                            Nuestro equipo de barberos expertos está listo para ofrecerte una experiencia de otro nivel.
                            Ya sea que busques un corte clásico, un diseño moderno o nuestro exclusivo
                            <em><strong>Paquete Premium</strong></em>, estamos aquí para ti.
                        </p>
                        <p style="font-size: 1.1rem;">
                            ¡No esperes más! Agenda tu cita con nosotros y prepárate para salir renovado.
                            Ponte en contacto a través de nuestro WhatsApp, redes sociales o visítanos directamente.
                            ¡Tu próxima transformación comienza aquí!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulario de Reserva -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <h4 class="text-center mb-4 text-white">¡Haz tu Reserva!</h4>
                            <form id="booking-form">
                                <div class="mb-3">
                                    <label for="name" class="form-label text-white">Nombre Completo:</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Ej: Juan Pérez" required>
                                </div>

                                <div class="mb-3">
                                    <label for="date" class="form-label text-white">Día de la Cita:</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>

                                <div class="mb-4">
                                    <label for="barber" class="form-label text-white">Barbero de Preferencia:</label>
                                    <select class="form-select" id="barber" name="barber" required>
                                        <option value="" disabled selected>Selecciona un barbero</option>
                                        <option value="Yeison Sarmiento">Yeison Sarmiento</option>
                                        <option value="Samuel">Samuel</option>
                                        <option value="El Menu">El Menu</option>
                                        <option value="Juancho">Juancho</option>
                                    </select>
                                </div>

                                <!-- Hora -->
                                <div class="mb-3">
                                    <label for="time" class="form-label text-white">Hora de la Cita:</label>
                                    <select class="form-select" id="time" name="time" required>
                                        <option value="" disabled selected>Selecciona una hora</option>
                                        <option value="08:00">08:00 AM</option>
                                        <option value="08:30">08:30 AM</option>
                                        <option value="09:00">09:00 AM</option>
                                        <option value="09:30">09:30 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="10:30">10:30 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="11:30">11:30 AM</option>
                                        <option value="14:00">02:00 PM</option>
                                        <option value="14:30">02:30 PM</option>
                                        <option value="15:00">03:00 PM</option>
                                        <option value="15:30">03:30 PM</option>
                                        <option value="16:00">04:00 PM</option>
                                        <option value="16:30">04:30 PM</option>
                                        <option value="17:00">05:00 PM</option>
                                        <option value="17:30">05:30 PM</option>
                                        <option value="18:00">06:00 PM</option>
                                        <option value="18:30">06:30 PM</option>
                                        <option value="19:00">07:00 PM</option>
                                        <option value="19:30">07:30 PM</option>
                                        <option value="20:00">08:00 PM</option>
                                        <option value="20:30">08:30 PM</option>
                                        <option value="21:00">09:00 PM</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    <a class="btn btn-neon" id="btn-reservar" target="_blank">RESERVAR</a>
                                    <a class="btn btn-neon" id="btn-cancelar" target="_blank">CANCELAR</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Generar las horas de 08:00 a 21:00 cada 30 minutos
        function generarHoras() {
            const horas = [];

            for (let h = 8; h <= 20; h++) {
                // Excluir 13:00 y 13:30 (1:00 PM y 1:30 PM)
                if (h === 13) continue;

                horas.push(`${String(h).padStart(2, '0')}:00`);
                horas.push(`${String(h).padStart(2, '0')}:30`);
            }

            // Agregar 21:00 (9:00 PM)
            horas.push("21:00");

            return horas;
        }

        function obtenerReservas() {
            return JSON.parse(localStorage.getItem("reservas")) || [];
        }

        function actualizarHoras() {
            const barbero = document.getElementById("barber").value;
            const fecha = document.getElementById("date").value;
            const horaSelect = document.getElementById("time");
            horaSelect.innerHTML = "";

            if (!barbero || !fecha) {
                horaSelect.innerHTML = `<option disabled selected>Selecciona fecha y barbero primero</option>`;
                return;
            }

            const horas = generarHoras();
            const reservas = obtenerReservas();

            horas.forEach(hora => {
                const ocupada = reservas.some(r =>
                    r.fecha === fecha && r.hora === hora && r.barbero === barbero
                );

                const option = document.createElement("option");
                option.value = hora;
                option.textContent = hora + (ocupada ? " - No disponible" : "");
                if (ocupada) option.disabled = true;

                horaSelect.appendChild(option);
            });
        }

        function esDispositivoMovil() {
            return /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        }

        function guardarReserva() {
            const nombre = document.getElementById("name").value.trim();
            const barbero = document.getElementById("barber").value.trim();
            const fecha = document.getElementById("date").value;
            const hora = document.getElementById("time").value;

            if (!nombre || !barbero || !fecha || !hora) {
                alert("⚠️ Por favor completa todos los campos antes de reservar.");
                return;
            }

            const reservas = obtenerReservas();

            const yaReservado = reservas.some(r =>
                r.fecha === fecha && r.hora === hora && r.barbero === barbero
            );

            if (yaReservado) {
                alert("⚠️ Esa hora ya fue reservada para ese barbero.");
                return;
            }

            reservas.push({
                nombre,
                barbero,
                fecha,
                hora
            });
            localStorage.setItem("reservas", JSON.stringify(reservas));

            // Mensaje para WhatsApp
            const telefono = "573124732236";
            const mensaje = `¡Hola! Quiero reservar una cita:\n` +
                `👤 Nombre: ${nombre}\n` +
                `📅 Fecha: ${fecha}\n` +
                `⏰ Hora: ${hora}\n` +
                `✂️ Barbero: ${barbero}`;

            const textoCodificado = encodeURIComponent(mensaje);
            const urlWhatsApp = esDispositivoMovil() ?
                `https://wa.me/${telefono}?text=${textoCodificado}` :
                `https://web.whatsapp.com/send?phone=${telefono}&text=${textoCodificado}`;

            window.open(urlWhatsApp, "_blank");

            // Limpiar formulario y horas
            document.getElementById("booking-form").reset();
            actualizarHoras();
        }

        // cancelar reserva
        document.getElementById("btn-cancelar").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("booking-form").reset();
            actualizarHoras();
        });

        // Eventos
        document.getElementById("date").addEventListener("change", actualizarHoras);
        document.getElementById("barber").addEventListener("change", actualizarHoras);
        document.querySelector(".btn-neon").addEventListener("click", function(e) {
            e.preventDefault();
            guardarReserva();
        });
    </script>
<main>