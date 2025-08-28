// Formulario de reserva
document.addEventListener('DOMContentLoaded', function () {

    const reservaForm = document.getElementById('reserva-form');
    if (reservaForm) {
        reservaForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const fecha = document.getElementById('fecha').value;
            const servicio = document.getElementById('servicio').value;

            console.log('Reserva recibida:', { nombre, email, fecha, servicio, });
            alert('¡Gracias por tu reserva! Nos comunicaremos contigo pronto para confirmarla.');
            this.reset();
        });
    }

    // Scroll suave en los links del menú
    document.querySelectorAll('a.nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Observador para animaciones
    const observerOptions = { threshold: 0.1 };
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible', 'is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('section').forEach(section => {
        section.classList.add('fade-in-section');
        observer.observe(section);
    });

    // Fondo animado del hero
    const heroBackgrounds = [
        'https://images.unsplash.com/photo-1621605815971-fbc98d665033?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
        'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80',
        'https://static.2x3cdn.com/assets/seo-keywords/desktop_tall/jpeg/fuoSxvpBQiuL.jpeg',
        'https://images.unsplash.com/photo-1517832606299-7ae9b720a186?ixlib=rb-4.0.3&auto=format&fit=crop&w=1169&q=80',
        'https://images.unsplash.com/photo-1590540179852-2110a54f813a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1169&q=80'
    ];

    let currentBackgroundIndex = 0;
    let heroBackground = document.querySelector('.hero-background');

    if (heroBackground) {
        heroBackground.style.backgroundImage = `linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('${heroBackgrounds[currentBackgroundIndex]}')`;

        function changeBackground() {
            const nextBackgroundIndex = (currentBackgroundIndex + 1) % heroBackgrounds.length;
            const nextBackground = heroBackgrounds[nextBackgroundIndex];

            const newBackground = document.createElement('div');
            newBackground.classList.add('hero-background');
            newBackground.style.backgroundImage = `linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('${nextBackground}')`;
            newBackground.style.opacity = '0';

            document.querySelector('.hero').insertBefore(newBackground, heroBackground.nextSibling);

            setTimeout(() => {
                newBackground.style.opacity = '1000';
                heroBackground.style.opacity = '0';
            }, 50);

            setTimeout(() => {
                heroBackground.remove();
                heroBackground = newBackground;
                currentBackgroundIndex = nextBackgroundIndex;
            }, 1000);
        }

        setInterval(changeBackground, 5000);
    }
});

// cerrar el menu al seleccionar una de las opciones de navegación
document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('.nav-link');
    const navbarCollapse = document.getElementById('navbarNav');

    navLinks.forEach(function (link) {
        link.addEventListener('click', function () {

            // Verificar si el menú está visible (modo móvil)
            const isVisible = window.getComputedStyle(navbarCollapse).display !== 'none';

            if (isVisible && navbarCollapse.classList.contains('show')) {

                // Cerrar el menú usando Collapse de Bootstrap
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    toggle: false
                });
                bsCollapse.hide();
            }
        });
    });
});

// Función para manejar los cambios de tamaño del navegador
function handleResponsiveLayout() {
    const width = window.innerWidth; // ancho de la pantalla
    const body = document.body;

    // Limpia clases anteriores (si hay)
    body.classList.remove('mobile-view', 'tablet-view', 'desktop-view');

    // Aplica clase según el tamaño
    if (width < 768) {
        // Teléfonos
        body.classList.add('mobile-view');
        console.log('Vista móvil activada');
    } else if (width >= 768 && width < 1024) {
        // Tablets
        body.classList.add('tablet-view');
        console.log('Vista tablet activada');
    } else {
        // PC de escritorio o portátil
        body.classList.add('desktop-view');
        console.log('Vista escritorio activada');
    }
}

// Llamar cuando la página se carga
window.addEventListener('DOMContentLoaded', handleResponsiveLayout);

// Llamar cada vez que se cambia el tamaño de la ventana
window.addEventListener('resize', handleResponsiveLayout);

// Página Web responsiva
function aplicarClaseResponsiva() {
    const width = window.innerWidth;
    const body = document.body;

    // Limpiar clases anteriores
    body.classList.remove('mobile-view', 'tablet-view', 'desktop-view');

    // Agregar clase según tamaño
    if (width < 768) {
        body.classList.add('mobile-view');
    } else if (width >= 768 && width < 1024) {
        body.classList.add('tablet-view');
    } else {
        body.classList.add('desktop-view');
    }
}

// Ejecutar al cargar y al cambiar el tamaño
window.addEventListener('DOMContentLoaded', aplicarClaseResponsiva);
window.addEventListener('resize', aplicarClaseResponsiva);


// Cierra el dropdown "Más" al seleccionar una opción dentro del dropdown
document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
    item.addEventListener('click', () => {
        // Cierra solo el dropdown
        const dropdownMenu = item.closest('.dropdown-menu');
        const dropdownToggle = dropdownMenu?.previousElementSibling;

        if (dropdownToggle && dropdownToggle.classList.contains('dropdown-toggle')) {
            const dropdownInstance = bootstrap.Dropdown.getInstance(dropdownToggle);
            if (dropdownInstance) {
                dropdownInstance.hide();
            }
        }

        // También cierra el menú hamburguesa si está abierto
        const navbarCollapse = document.querySelector('.navbar-collapse');
        if (navbarCollapse.classList.contains('show')) {
            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        }
    });
});

// Cierra el menú hamburguesa al dar clic en un link del navbar (excepto "Más")
document.querySelectorAll('.navbar-collapse .nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
        // Si NO es el botón "Más", cierra el menú hamburguesa
        if (!link.classList.contains('dropdown-toggle')) {
            const navbarCollapse = document.querySelector('.navbar-collapse');
            if (navbarCollapse.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        }
    });
});