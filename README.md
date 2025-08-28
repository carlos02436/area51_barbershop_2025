# AREA_51_BARBER_SHOP
Página web, para negocio de Barbería.

AREA51_BARBERSHOP_2025/
│── BD/                       # Scripts SQL (estructura y datos iniciales)
│   └── BD_area51.sql
│
│── app/                      # Carpeta principal del MVC
│   ├── controllers/          # Controladores: lógica de negocio
│   │   ├── ClienteController.php
│   │   ├── CitaController.php
│   │   ├── ServicioController.php
│   │   └── AuthController.php
│   │
│   ├── models/               # Modelos: conexión y consultas a la BD
│   │   ├── Cliente.php
│   │   ├── Cita.php
│   │   ├── Servicio.php
│   │   └── Usuario.php
│   │
│   ├── views/                # Vistas: interfaz HTML/PHP
│   │   ├── clientes/
│   │   │   ├── index.php
│   │   │   ├── crear.php
│   │   │   └── editar.php
│   │   ├── citas/
│   │   │   ├── index.php
│   │   │   └── detalle.php
│   │   └── servicios/
│   │       └── index.php
│   │
│   └── core/                 # Configuración central
│       ├── Database.php      # Conexión PDO
│       └── App.php           # Ruteador principal
│
│── public/                   # Archivos accesibles desde navegador
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── scripts.js
│   └── img/
│
│── index.php                 # Punto de entrada del MVC
│── .gitignore
│── README.md
