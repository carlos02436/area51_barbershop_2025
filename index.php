<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área 51_Barber Shop</title>

    <!-- Bootstrap y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Icono -->
    <link rel="icon" href="public/img/logo.png">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap"
        rel="stylesheet">

    <!-- Estilos CSS -->
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

    <!-- Encabezado -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top" style="margin: 15px;border-radius: 25px;
        border-inline: 2px solid #00ff00;">
        <div class="container-fluid">
            <a class="navbar-brand">
                <img src="public/img/logo.png" alt="Logo REDOLLS S.A." class="img-fluid text-white" style="height: 50px;">
                <strong class="text-white">Area 51_Barbershop</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-4">
                        <a class="nav-link text-white" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link text-white" href="#nosotros">Nosotros</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link text-white" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item dropdown me-4">
                        <a class="nav-link dropdown-toggle text-white" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Más
                        </a>
                        <ul class="dropdown-menu dropdown-menu-start bg-dark ms-n2">
                            <li><a class="dropdown-item text-white bg-dark" href="#barberos">Barberos</a></li>
                            <li><a class="dropdown-item text-white bg-dark" href="#galeria">Galería</a></li>
                            <li><a class="dropdown-item text-white bg-dark" href="#videos">Videos</a></li>
                            <li><a class="dropdown-item text-white bg-dark" href="#noticias">Noticias</a></li>
                            <li><a class="dropdown-item text-white bg-dark" href="#testimonios">Testimonios</a></li>
                        </ul>
                    <li class="nav-item me-4">
                        <a class="nav-link text-white" href="#contacto">Contáctame</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header id="inicio" class="hero" style="z-index: -1;border-radius: 40% 10% 60%/20% 80% 20%;">
        <div class="hero-background"></div>
        <div class="container">
            <h1 class="fs-1">Área 51_Barber Shop</h1>
            <p class="lead">Descubre tu Mejor Estilo</p>
        </div>
    </header>

    <!-- Botón Scroll hacia abajo y devuelve al inicio -->
    <button id="scrollToTopBtn" class="btn" style="position:fixed; bottom:40px; right:30px; z-index:9999; width: 50px; height:40px;
        display:none; align-items:center; justify-content:center;">
        <i class="fa-solid fa-chevron-up fa-lg"></i>
    </button>

    <!-- Código JS para el Botón Scroll hacia abajo y devuelve al inicio -->
    <script>
        // Mostrar/ocultar el botón al hacer scroll
        window.addEventListener('scroll', function() {
            const btn = document.getElementById('scrollToTopBtn');
            if (window.scrollY > 200) {
                btn.style.display = 'flex';
            } else {
                btn.style.display = 'none';
            }
        });

        // Scroll suave al inicio al hacer clic
        document.getElementById('scrollToTopBtn').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

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
    <section id="servicios" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Nuestros Servicios</h2>
            <div class="row">
                <!-- Tarjeta 1 -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                            <div
                                style="width: 100%; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 20px;">
                                <img src="public/img/corte1.jpg" alt="Barbero Yeison en Área 51"
                                    style="width: 97%; height: 97%; object-fit: cover; border-radius: 20px;">
                            </div>
                            <h5 class="card-title"><i class="fas fa-cut me-2"></i>Corte de Cabello</h5>
                            <p class="card-text">Corte de cabello diseñado para que destaques.</p>
                            <p class="card-text"><strong>Precio: $15.000,00</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2 -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                            <div
                                style="width: 100%; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 20px;">
                                <img src="public/img/corte de cabello y barba1.jpg" alt="Barbero Yeison en Área 51"
                                    style="width: 97%; height: 97%; object-fit: cover; border-radius: 20px;">
                            </div>
                            <h5 class="card-title"><i class="fas fa-cut me-2"></i>Corte de Cabello y Corte de Barba</h5>
                            <p class="card-text">Afeitado y Corte de Barba clásico, siempre resaltando tu mejor estilo.
                            </p>
                            <p class="card-text"><strong>Precio: $25.000,00</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 3 -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                            <div
                                style="width: 100%; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 20px;">
                                <img src="public/img/corte de cabello y cejas.jpg" alt="Barbero Yeison en Área 51"
                                    style="width: 97%; height: 97%; object-fit: cover; border-radius: 20px;">
                            </div>
                            <h5 class="card-title"><i class="fas fa-cut me-2"></i>Corte de Cabello y Corte de Cejas</h5>
                            <p class="card-text">Siempre innovando y utilizando productos de excelente calidad.</p>
                            <p class="card-text"><strong>Precio: $18.000,00</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 4 -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                            <div
                                style="width: 100%; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 20px;">
                                <img src="public/img/corte de barba.jpg" alt="Barbero Yeison en Área 51"
                                    style="width: 97%; height: 97%; object-fit: cover; border-radius: 20px;">
                            </div>
                            <h5 class="card-title"><i class="fas fa-cut me-2"></i>Corte de Barba</h5>
                            <p class="card-text">Diferentes cortes y perfilación de barba, al mejor estilo de Área_51
                                Barber Shop.</p>
                            <p class="card-text"><strong>Precio: $10.000,00</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 5 -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                            <div
                                style="width: 100%; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 20px;">
                                <img src="public/img/corte de cejas.jpg" alt="Barbero Yeison en Área 51"
                                    style="width: 97%; height: 97%; object-fit: cover; border-radius: 20px;">
                            </div>
                            <h5 class="card-title"><i class="fas fa-cut me-2"></i>Corte de Cejas</h5>
                            <p class="card-text">Perfilación de cejas, que se adapta a tu estilo.</p>
                            <p class="card-text"><strong>Precio: $5.000,00</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 6 -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                            <div
                                style="width: 100%; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 20px;">
                                <img src="public/img/cerquillo.jpg" alt="Barbero Yeison en Área 51"
                                    style="width: 97%; height: 97%; object-fit: cover; border-radius: 20px;">
                            </div>
                            <h5 class="card-title"><i class="fas fa-cut me-2"></i>Cerquillo</h5>
                            <p class="card-text">Delineación de cortes, aplicando las mejores técnicas para resaltar tu
                                estilo.</p>
                            <p class="card-text"><strong>Precio: $5.000,00</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 7 -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                            <div
                                style="width: 100%; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 20px;">
                                <img src="public/img/paquete premium.jpg" alt="Barbero Yeison en Área 51"
                                    style="width: 97%; height: 97%; object-fit: cover; border-radius: 20px;">
                            </div>
                            <h5 class="card-title"><i class="fas fa-star-of-life me-2"></i>Paquete Premium</h5>
                            <p class="card-text">Combinación de corte de cabello, arreglo de barba, limpieza facial y
                                masaje relajante.</p>
                            <p class="card-text"><strong>Precio: $50.000,00</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 8 -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                            <div
                                style="width: 100%; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border-radius: 20px;">
                                <img src="public/img/keratina.jpg" alt="Barbero Yeison en Área 51"
                                    style="width: 97%; height: 97%; object-fit: cover; border-radius: 20px;">
                            </div>
                            <h5 class="card-title"><i class="fas fa-star-of-life me-2"></i>Aplicación de Queratina</h5>
                            <p class="card-text">Renueva tu cabello con nuestra aplicación de queratina, dejándolo
                                suave, brillante y manejable.</p>
                            <p class="card-text">Costo según el largo del cabello.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Barberos -->
    <section id="barberos" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Sección de Barberos</h2>
            <div class="container">
                <!-- Barbero 1 -->
                <div class="row align-items-center mb-5">
                    <div class="col-md-6">
                        <img src="public/img/yeisonBarber.png" alt="Barbero 1"
                            style="border-radius: 20px; width: 70%; height: 70%;">
                    </div>
                    <div class="col-md-6">
                        <h2 class="text-center mb-4 section-title">Yeison Sarmiento</h2>
                        <p class="card-text">
                            Experto en cortes clásicos y modernos, enfocado en resaltar la personalidad de cada cliente.
                            Su pasión y dedicación lo convierten en uno de los barberos favoritos de nuestros
                            visitantes.
                        </p>
                    </div>
                </div>

                <!-- Barbero 2 -->
                <div class="row align-items-center mb-5 flex-md-row-reverse">
                    <div class="col-md-6">
                        <img src="public/img/yeisonBarber.png" alt="Barbero 1"
                            style="border-radius: 20px; width: 70%; height: 70%;">
                    </div>
                    <div class="col-md-6">
                        <h2 class="text-center mb-4 section-title">El menu</h2>
                        <p class="card-text">
                            Especialista en estilos urbanos y tendencias actuales. Su creatividad y técnica impecable
                            aseguran que cada cliente salga con un look fresco e innovador.
                        </p>
                    </div>
                </div>

                <!-- Barbero 3 -->
                <div class="row align-items-center mb-5">
                    <div class="col-md-6">
                        <img src="public/img/yeisonBarber.png" alt="Barbero 1"
                            style="border-radius: 20px; width: 70%; height: 70%;">
                    </div>
                    <div class="col-md-6">
                        <h2 class="text-center mb-4 section-title">Samuél</h2>
                        <p class="card-text">
                            Con años de experiencia, su especialidad son los fades perfectos y el cuidado de la barba.
                            Su atención al detalle garantiza resultados excepcionales en cada visita.
                        </p>
                    </div>
                </div>

                <!-- Barbero 4 -->
                <div class="row align-items-center mb-5 flex-md-row-reverse">
                    <div class="col-md-6">
                        <img src="public/img/yeisonBarber.png" alt="Barbero 1"
                            style="border-radius: 20px; width: 70%; height: 70%;">
                    </div>
                    <div class="col-md-6">
                        <h2 class="text-center mb-4 section-title">Juancho</h2>
                        <p class="card-text">
                            Amante de los cortes de precisión y los estilos personalizados. Su objetivo es realzar la
                            imagen de cada cliente a través de técnicas modernas y asesoría personalizada.
                        </p>
                    </div>
                </div>

            </div>
    </section>

    <!-- Galería de Fotos -->
    <section id="galeria" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Galería de estilos</h2>
            <div class="row">
                <div class="col-md-4 col-6 mb-4">
                    <img src="public/img/corte7.png" alt="Estilo de corte y barba 1" class="img-fluid"
                        style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <img src="public/img/corte8.png" alt="Estilo de corte y barba 2" class="img-fluid"
                        style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <img src="public/img/corte9.png" alt="Estilo de corte y barba 3" class="img-fluid"
                        style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <img src="public/img/corte10.png" alt="Estilo de corte y barba 4" class="img-fluid"
                        style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <img src="public/img/corte11.png" alt="Estilo de corte y barba 5" class="img-fluid"
                        style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <img src="public/img/corte12.png" alt="Estilo de corte y barba 5" class="img-fluid"
                        style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <img src="public/img/corte13.png" alt="Estilo de corte y barba 5" class="img-fluid"
                        style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <img src="public//corte14.png" alt="Estilo de corte y barba 5" class="img-fluid"
                        style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <img src="public/img/corte15.png" alt="Estilo de corte y barba 5" class="img-fluid"
                        style="width: 100%; height: 90%; object-fit: cover; border-radius: 20px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Videos -->
    <section id="videos" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Videos y algo más...</h2>
            <div class="row g-4" id="video-container">

                <!-- YouTube Videos -->
                <div class="col-md-4 mb-4">
                    <div class="embed-responsive embed-responsive-16by9 video-wrapper">
                        <iframe class="embed-responsive-item"
                            src="https://www.youtube.com/embed/aTtXGgfjKCs?si=_knqaLDocdSN5hON"
                            allowfullscreen></iframe>
                    </div>
                    <h5 class="mt-2 text-center">Técnicas de Corte</h5>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="embed-responsive embed-responsive-16by9 video-wrapper">
                        <iframe class="embed-responsive-item"
                            src="https://www.youtube.com/embed/pGjfODLWKxQ?si=ePJuG0kaKjJ0TodK"
                            allowfullscreen></iframe>
                    </div>
                    <h5 class="mt-2 text-center">Estílos de Barba</h5>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="embed-responsive embed-responsive-16by9 video-wrapper">
                        <iframe class="embed-responsive-item"
                            src="https://www.youtube.com/embed/iZm-WVWTAqM?si=rjpJvvUIVEfByVqW"
                            allowfullscreen></iframe>
                    </div>
                    <h5 class="mt-2 text-center">Estílos de Corte</h5>
                </div>

                <!-- TikTok Videos -->
                <div class="col-md-6 col-lg-4 d-flex justify-content-center">
                    <blockquote class="tiktok-embed iframe"
                        cite="https://www.tiktok.com/@ysarmiento.barber/video/7492250638236110086"
                        data-video-id="7492250638236110086">
                        <section></section>
                    </blockquote>
                </div>

                <div class="col-md-6 col-lg-4">
                    <blockquote class="tiktok-embed"
                        cite="https://www.tiktok.com/@ysarmiento.barber/video/7493208342282767621"
                        data-video-id="7493208342282767621">
                        <section></section>
                    </blockquote>
                </div>

                <div class="col-md-6 col-lg-4 d-flex justify-content-center">
                    <blockquote class="tiktok-embed"
                        cite="https://www.tiktok.com/@ysarmiento.barber/video/7494847153395813637"
                        data-video-id="7494847153395813637">
                        <section></section>
                    </blockquote>
                </div>

            </div>
        </div>
    </section>
    <!-- Scripts de TikTok -->
    <script async src="https://www.tiktok.com/embed.js"></script>
    </section>

    <!-- Noticias -->
    <section id="noticias" class="py-5 fade-in-section" style="scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Noticias y Eventos</h2>
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body d-flex flex-column text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);">
                            <h5 class="card-title"><i class="far fa-calendar-alt me-2"></i>Evento: Noche de Estilo</h5>
                            <p class="card-text">¡Prepárense amigos! El próximo sábado tendremos una noche especial
                                con descuentos en cortes y estilos inspirados en el cosmos. Música, bebidas y buen
                                ambiente garantizados.</p>
                            <p class="card-text mt-auto"><small>Publicado: Hace 2 días</small></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body d-flex flex-column text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);">
                            <h5 class="card-title"><i class="fas fa-bullhorn me-2"></i>Nuevo Producto: Cera</h5>
                            <p class="card-text">Hemos recibido nuestra nueva cera para peinar "Gravedad Cero". Fijación
                                extrema que desafía las leyes de la física. ¡Pide una muestra en tu próxima visita!</p>
                            <p class="card-text mt-auto"><small>Publicado: Hace 1 semana</small></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 bg-transparent border-0">
                        <div class="card-body d-flex flex-column text-white"
                            style="background: rgba(0, 0, 0, 0.5); border-radius: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);">
                            <h5 class="card-title"><i class="fas fa-gift me-2"></i>Promoción Aniversario</h5>
                            <p class="card-text">Celebramos nuestro primer año interestelar. Durante todo el mes, obtén
                                un 15% de descuento en el "Paquete Barber Shop" al reservar online.</p>
                            <p class="card-text mt-auto"><small>Publicado: Hace 3 semanas</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Testimonios -->
    <section id="testimonios" class="py-5 fade-in-section"
        style="padding-top: 150px; padding-bottom: 150px; margin: auto; scroll-margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Testimonios</h2>

            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" style="height: 200px;">

                    <!-- Testimonio 1 -->
                    <div class="carousel-item active" style="margin: 15px auto;">
                        <div class="d-flex flex-column align-items-center">
                            <img src="public/img/yeisonBarber.png" alt="Juan Pérez" class="rounded-circle mb-4"
                                style="width: 90px; height: 90px; object-fit: cover;">
                            <p class="text-center mb-4">"Excelente servicio, el ambiente es espectacular. ¡Súper
                                recomendado!"</p>
                            <h5>Juan Pérez</h5>
                        </div>
                    </div>

                    <!-- Testimonio 2 -->
                    <div class="carousel-item" style="margin: 15px auto;">
                        <div class="d-flex flex-column align-items-center">
                            <img src="public/img/yeisonBarber.png" alt="Andrés Gómez" class="rounded-circle mb-4"
                                style="width: 90px; height: 90px; object-fit: cover;">
                            <p class="text-center mb-4">"El mejor corte que me han hecho, atención personalizada y
                                profesionalismo."</p>
                            <h5>Andrés Gómez</h5>
                        </div>
                    </div>

                    <!-- Testimonio 3 -->
                    <div class="carousel-item" style="margin: 15px auto;">
                        <div class="d-flex flex-column align-items-center">
                            <img src="public/img/yeisonBarber.png" alt="Camilo Torres" class="rounded-circle mb-4"
                                style="width: 90px; height: 90px; object-fit: cover;">
                            <p class="text-center mb-4">"Un lugar increíble donde te hacen sentir como en casa. Muy
                                recomendado."</p>
                            <h5>Camilo Torres</h5>
                        </div>
                    </div>

                    <!-- Testimonio 4 -->
                    <div class="carousel-item" style="margin: 15px auto;">
                        <div class="d-flex flex-column align-items-center">
                            <img src="public/img/yeisonBarber.png" alt="Sebastián Martínez" class="rounded-circle mb-4"
                                style="width: 90px; height: 90px; object-fit: cover;">
                            <p class="text-center mb-4">"Cortes modernos y el personal súper amable. Sin duda volveré."
                            </p>
                            <h5>Sebastián Martínez</h5>
                        </div>
                    </div>

                    <!-- Testimonio 5 -->
                    <div class="carousel-item" style="margin: 15px auto;">
                        <div class="d-flex flex-column align-items-center">
                            <img src="public/img/yeisonBarber.png" alt="Diego Herrera" class="rounded-circle mb-4"
                                style="width: 90px; height: 90px; object-fit: cover;">
                            <p class="text-center mb-4">"Muy buena experiencia, me encantó la asesoría sobre el estilo
                                que más me convenía."</p>
                            <h5>Diego Herrera</h5>
                        </div>
                    </div>

                    <!-- Testimonio 6 -->
                    <div class="carousel-item" style="margin: 15px auto;">
                        <div class="d-flex flex-column align-items-center">
                            <img src="public/img/yeisonBarber.png" alt="Luis Ramírez" class="rounded-circle mb-4"
                                style="width: 90px; height: 90px; object-fit: cover;">
                            <p class="text-center mb-4">"Profesionales de verdad, te sientes en manos expertas desde el
                                primer momento."</p>
                            <h5>Luis Ramírez</h5>
                        </div>
                    </div>

                    <!-- Testimonio 7 -->
                    <div class="carousel-item" style="margin: 15px auto;">
                        <div class="d-flex flex-column align-items-center">
                            <img src="public/img/yeisonBarber.png" alt="Ricardo Castaño" class="rounded-circle mb-4"
                                style="width: 90px; height: 90px; object-fit: cover;">
                            <p class="text-center mb-4">"Simplemente los mejores, me devolvieron la confianza en los
                                barberos."</p>
                            <h5>Ricardo Castaño</h5>
                        </div>
                    </div>

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

    <!-- Footer -->
    <footer class="py-4" style="background: #000000; color: white;">
        <div class="container">
            <div class="row text-center text-md-start">
                <!-- FOOTER INICIO -->
                <div class="col-md-2 mb-3">
                    <p class="navbar-brand mb-0">HOME</p>
                    <ul class="list-unstyled">
                        <li><a href="#inicio" style="color: white; text-decoration: none;">Inicio</a></li>
                        <li><a href="#servicios" style="color: white; text-decoration: none;">Servicios</a></li>
                        <li><a href="#galeria" style="color: white; text-decoration: none;">Galería</a></li>
                        <li><a href="#barberos" style="color: white; text-decoration: none;">Barberos</a></li>
                        <li><a href="#contacto" style="color: white; text-decoration: none;">Contacto</a></li>
                    </ul>
                </div>

                <!-- FOOTER SERVICIOS -->
                <div class="col-md-2 mb-3">
                    <p class="navbar-brand mb-0">SERVICIOS</p>
                    <ul class="list-unstyled">
                        <li><a href="#servicios" style="color: white; text-decoration: none;">Cortes</a></li>
                        <li><a href="#servicios" style="color: white; text-decoration: none;">Afeitado</a></li>
                        <li><a href="#servicios" style="color: white; text-decoration: none;">Diseños</a></li>
                    </ul>
                </div>

                <!-- FOOTER NOSOTROS -->
                <div class="col-md-2 mb-3">
                    <p class="navbar-brand mb-0">NOSOTROS</p>
                    <ul class="list-unstyled">
                        <li><a href="#nosotros" style="color: white; text-decoration: none;">Quiénes Somos</a></li>
                        <li><a href="#nosotros" style="color: white; text-decoration: none;">Misión</a></li>
                        <li><a href="#nosotros" style="color: white; text-decoration: none;">Visión</a></li>
                        <li><a href="#testimonios" style="color: white; text-decoration: none;">Testimonios</a></li>
                    </ul>
                </div>

                <!-- FOOTER CONTACTO -->
                <div class="col-md-2 mb-3">
                    <p class="navbar-brand mb-0">CONTACTO</p>
                    <ul class="list-unstyled">
                        <li><a href="tel:+57 312 292 30 91" style="color: white; text-decoration: none;">(+57) 312 473
                                22 36</a></li>
                        <li><a href="https://mail.google.com/mail/?view=cm&fs=1&to=area51barbershop2025@gmail.com"
                                target="_blank"
                                style="color: white; text-decoration: none;">area51barbershop2025@gmail.com</a></li>
                        <li><a style="color: white; text-decoration: none;">La Jagua de Ibirico, Cesar</a></li>
                    </ul>
                </div>

                <!-- LOGO + BOTONES -->
                <div class="col-md-4 mb-3 text-center">
                    <img src="img/logo.png" alt="Área 51_Barber Shop"
                        style="width: 40%; height: inherit; margin-bottom: 15px;">
                    <div class="d-flex justify-content-center gap-2 mt-2">
                    </div>
                </div>

                <hr style="border-top: 1px solid #00ff00;">
                <div class="text-center mt-3">
                    <p class="navbar-brand mb-0" style="justify-content: center;font-size: 1.3rem;">
                        &copy; 2025 Área 51_Barber Shop. Todos los Derechos Reservados.</p>
                </div>
            </div>
    </footer>

    <script src="public/JavaScript/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts de TikTok -->
    <script async src="https://www.tiktok.com/embed.js"></script>

</body>

</html>