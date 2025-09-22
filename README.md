AREA_51_BARBER_SHOP

AREA_51_BARBER_SHOP es una página web diseñada para la gestión integral de un negocio de barbería. Su objetivo principal es facilitar a los clientes la posibilidad de agendar citas de manera rápida, comunicarse directamente con los barberos y acceder a información sobre servicios, promociones, noticias y otros contenidos relacionados con el negocio.

Estructura del Proyecto

El proyecto está organizado de la siguiente manera:

AREA51_BARBERSHOP_2025/
│
├── app/
│   ├── controllers/      # Controladores (lógica de negocio)
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
│   ├── models/           # Modelos (acceso a datos)
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
│   ├── views/            # Vistas (interfaz de usuario)
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
│   └── BD/               # Scripts SQL de la base de datos
│       └── area51_barberia.sql
│
├── config/               # Configuración general
│   └── database.php
│
├── public/               # Archivos públicos (assets)
│   ├── css/
│   │   └── style.css
│   ├── JavaScript/
│   │   └── scripts.js
│   └── img/
│
├── .gitignore            # Archivos ignorados por Git
├── index.php             # Punto de entrada del proyecto
└── README.md             # Documentación del proyecto

Descripción de Componentes

Controllers: Gestionan la lógica de negocio y la comunicación entre vistas y modelos.
Models: Encargados de la interacción con la base de datos, encapsulando consultas y transacciones.
Views: Plantillas HTML/PHP que construyen la interfaz visual del usuario.
Public: Carpeta con recursos como CSS, JavaScript e imágenes.
Config: Contiene la configuración de la base de datos y parámetros globales.
BD: Incluye los scripts SQL necesarios para crear y poblar la base de datos.

Funcionalidades Principales

Registro y gestión de clientes y barberos.
Administración completa de citas: creación, edición, cancelación y confirmación.
Panel de administración con métricas y estadísticas.
Panel de administración para la gestión de servicios y promociones.
Galería de imágenes y sección de noticias.
Integración de videos promocionales y contenido de TikTok.

Gestión de PQRS (Peticiones, Quejas, Reclamos y Sugerencias).

Registro de ingresos y generación de reportes diarios, semanales y mensuales.
Requisitos Técnicos
PHP 8.x o superior.
MySQL/MariaDB como motor de base de datos.
Servidor web compatible (XAMPP, WAMP o similar).
Composer para gestionar dependencias externas.

Instalación

Clonar el repositorio:

git clone <url-del-repositorio>
Configurar la base de datos en config/database.php.
Importar el script area51_barberia.sql en el servidor MySQL.
Colocar la carpeta AREA51_BARBERSHOP_2025/public como raíz del servidor web.
Acceder a http://localhost/index.php para probar la aplicación.

Derechos de Autor y Uso

Todo el contenido y código de AREA_51_BARBER_SHOP es propiedad exclusiva de Carlos Enrique Parra Castañeda. Está prohibida la modificación, distribución o uso del sistema sin autorización expresa del autor.

La plataforma facilita la comunicación entre clientes y barberos, pero la confirmación, cumplimiento y calidad del servicio depende directamente de ambas partes.

Responsabilidades del Sistema

Garantizar que el mensaje de confirmación de cita llegue al barbero seleccionado.
No se responsabiliza por cancelaciones, cambios de horario o retrasos una vez enviado el mensaje de confirmación.

Contacto

Desarrollador: Carlos Enrique Parra Castañeda
Email: cparra02436@gmail.com
Sitio Web: Área 51 Barbershop

Licencia

Propiedad reservada © 2025 Carlos Enrique Parra Castañeda.
Todos los derechos sobre este sistema y su código fuente están estrictamente reservados.

Condiciones de uso:

Uso limitado al proyecto personal y comercial autorizado por el autor.
Prohibida la copia, modificación, distribución, sublicenciamiento o explotación parcial o total sin autorización escrita.
No se permite eliminar notas de autoría ni avisos legales del software.
Prohibido usar el sistema como base para proyectos derivados sin permiso.

Responsabilidad:

El desarrollador no asume responsabilidad por usos indebidos del sistema.
La responsabilidad se limita al correcto envío de la información de citas entre cliente y barbero.
Cualquier incumplimiento, cancelación o modificación del servicio tras la confirmación es responsabilidad exclusiva del cliente y del barbero.

Derechos exclusivos:

Solo Carlos Enrique Parra Castañeda puede realizar modificaciones, mejoras o actualizaciones.

El uso no autorizado podrá ser perseguido legalmente bajo la normativa de derechos de autor en Colombia y tratados internacionales (Convenio de Berna, DMCA, entre otros).