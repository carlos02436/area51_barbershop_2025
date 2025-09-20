document.addEventListener('DOMContentLoaded', function () {

    // --------------------------
    // FORMULARIO DE RESERVA
    // --------------------------
    const reservaForm = document.getElementById('reserva-form');
    if (reservaForm) {
        reservaForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const fecha = document.getElementById('fecha').value;
            const servicio = document.getElementById('servicio').value;

            console.log('Reserva recibida:', { nombre, email, fecha, servicio });
            alert('¡Gracias por tu reserva! Nos comunicaremos contigo pronto para confirmarla.');

            this.reset();
        });
    }

    // --------------------------
    // SCROLL SUAVE CON NAVBAR FIJO
    // --------------------------
    const navbarHeight = document.querySelector('.navbar')?.offsetHeight || 80;

    document.querySelectorAll('a.nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            const targetSelector = this.getAttribute('href');
            const target = document.querySelector(targetSelector);

            if (target && window.location.hash !== targetSelector) {
                e.preventDefault();
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                window.scrollTo({ top: targetPosition, behavior: 'smooth' });

                // Actualizar el hash sin saltar
                history.pushState(null, null, targetSelector);
            }
        });
    });

    // --------------------------
    // OBSERVADOR PARA ANIMACIONES
    // --------------------------
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

    // --------------------------
    // FONDO ANIMADO DEL HERO
    // --------------------------
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
                newBackground.style.opacity = '1';
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

    // --------------------------
    // CIERRE AUTOMÁTICO DEL MENÚ HAMBURGUESA
    // --------------------------
    const navLinks = document.querySelectorAll('.nav-link');
    const navbarCollapse = document.getElementById('navbarNav');

    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            const isVisible = window.getComputedStyle(navbarCollapse).display !== 'none';
            if (isVisible && navbarCollapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, { toggle: false });
                bsCollapse.hide();
            }
        });
    });

    // --------------------------
    // CIERRE DE DROPDOWN "MÁS"
    // --------------------------
    document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            const dropdownMenu = item.closest('.dropdown-menu');
            const dropdownToggle = dropdownMenu?.previousElementSibling;

            if (dropdownToggle && dropdownToggle.classList.contains('dropdown-toggle')) {
                const dropdownInstance = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (dropdownInstance) dropdownInstance.hide();
            }

            if (navbarCollapse.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) bsCollapse.hide();
            }
        });
    });

});