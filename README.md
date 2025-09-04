# AREA_51_BARBER_SHOP
Página web, para negocio de Barbería.

AREA51_BARBERSHOP_2025/
│
├── app/                                
│   ├── controllers/                    # Controladores (lógica de negocio)
│   │   ├── AuthController.php
│   │   ├── BarberoController.php
│   │   ├── CitasController.php
│   │   ├── DashboardController.php
│   │   ├── GaleriaController.php
│   │   ├── NoticiaController.php
│   │   ├── PanelController.php
│   │   ├── ReportesController.php
│   │   ├── ServicioController.php
│   │   ├── TikTokController.php
│   │   └── VideoController.php
│   │
│   ├── models/                         # Modelos (acceso a datos)
│   │   ├── Barbero.php
│   │   ├── Cita.php
│   │   ├── Cliente.php
│   │   ├── Dashboard.php
│   │   ├── Galeria.php
│   │   ├── Noticia.php
│   │   ├── Reporte.php
│   │   ├── Servicio.php
│   │   ├── Testimonio.php
│   │   ├── TikTok.php
│   │   └── Video.php
│   │
│   ├── views/                          # Vistas (interfaz de usuario)
│   │   ├── barberos/
│   │   │   ├── create.php
│   │   │   ├── edit.php
│   │   │   └── index.php
│   │   ├── citas/
│   │   │   ├── create.php
│   │   │   ├── delete.php
│   │   │   └── edit.php
│   │   ├── plantillas/
│   │   │   ├── footer.php
│   │   │   └── header.php
│   │   ├── dashboard.php
│   │   ├── home.php
│   │   ├── login.php
│   │   ├── logout.php
│   │   └── panel.php
│   │
│   └── BD/                             # Base de datos (scripts SQL)
│       └── area51_barberia.sql
│
├── config/                             # Configuración general
│   └── database.php
│
├── public/                             # Archivos públicos (assets)
│   ├── css/
│   │   └── style.css
│   ├── JavaScript/
│   │   └── scripts.js
│   └── img/
│
├── .gitignore                          # Ignorar archivos en Git
├── index.php                           # Punto de entrada del proyecto
└── README.md                           # Documentación inicial
