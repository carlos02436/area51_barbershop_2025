-- ==========================================
-- Crear base de datos
-- ==========================================
DROP DATABASE IF EXISTS `area51_barbershop_2025`;
CREATE DATABASE `area51_barbershop_2025`;
USE `area51_barbershop_2025`;

-- --------------------------------------------------------
-- Tabla: administradores
-- --------------------------------------------------------
CREATE TABLE `administradores` (
  `id_admin` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `usuario` VARCHAR(50) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `creado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `administradores` (`id_admin`, `nombre`, `usuario`, `email`, `password`, `creado_en`) VALUES
(1, 'Carlos Parra', 'cparra', 'carlos.parra@example.com', '12345', '2025-08-29 20:16:49'),
(2, 'Yeison Sarmiento', 'ysarmiento', 'yeison.sarmiento@example.com', '12345', '2025-08-29 20:16:49'),
(3, 'Yisel Herrera', 'yiselHr', 'yisel@example.com', '12345', '2025-08-29 20:16:49');

DELIMITER $$
CREATE TRIGGER `limite_administradores` BEFORE INSERT ON `administradores`
FOR EACH ROW
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM administradores;
    IF total >= 3 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se pueden registrar más de 3 administradores.';
    END IF;
END$$
DELIMITER ;

-- --------------------------------------------------------
-- Tabla: barberos
-- --------------------------------------------------------
CREATE TABLE `barberos` (
  `id_barbero` INT(11) NOT NULL AUTO_INCREMENT,
  `img_barberos` VARCHAR(255) DEFAULT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `especialidad` VARCHAR(500) NOT NULL,
  `telefono` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(100) NULL,
  `fecha_contratacion` DATE DEFAULT NULL,
  PRIMARY KEY (`id_barbero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `barberos` (`id_barbero`, `img_barberos`, `nombre`, `especialidad`, `telefono`, `fecha_contratacion`) VALUES
(1, 'yeisonBarber.png', 'Yeison Sarmiento', 'Es un barbero excelente, con gran precisión en los cortes y un estilo moderno que encanta a los clientes. Su dedicación y creatividad hacen que cada visita sea una experiencia única y satisfactoria.', '3112345678', '2024-12-05'),
(2, 'Rafael_Barrios.png', 'Rafael Barrios', 'Destaca por su técnica impecable y su pasión por ofrecer siempre un corte de calidad superior. Su compromiso con la perfección lo ha convertido en uno de los barberos más confiables y solicitados por los clientes.', '3123456789', '2024-12-10'),
(3, 'Rafael_Jaime.png', 'Rafael Jaime', 'Es reconocido por su estilo detallista, garantizando siempre un acabado elegante y profesional. Su habilidad para adaptarse a las tendencias lo hace sobresalir como un referente en la barbería.', '3134567890', '2024-12-15'),
(4, 'Samuel_Martinez.png', 'Samuel Martínez', 'Es un barbero con gran creatividad, logrando cortes únicos y personalizados que dejan huella en cada cliente. Su talento y carisma generan confianza, lo que hace que siempre sea altamente recomendado.', '3145678901', '2024-12-18');

-- --------------------------------------------------------
-- Tabla: clientes
-- --------------------------------------------------------
CREATE TABLE `clientes` (
  `id_cliente` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NOT NULL,
  `telefono` VARCHAR(20) DEFAULT NULL,
  `correo` VARCHAR(150) DEFAULT NULL,
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `telefono`, `correo`, `fecha_registro`) VALUES
(1, 'Carlos', 'Pérez', '3001234567', 'carlos.perez@mail.com', '2025-01-05 05:00:00'),
(2, 'Andrés', 'Gómez', '3012345678', 'andres.gomez@mail.com', '2025-01-10 05:00:00'),
(3, 'Juan', 'Rodríguez', '3023456789', 'juan.rodriguez@mail.com', '2025-01-12 05:00:00'),
(4, 'Felipe', 'Ramírez', '3034567890', 'felipe.ramirez@mail.com', '2025-01-15 05:00:00'),
(5, 'David', 'Torres', '3045678901', 'david.torres@mail.com', '2025-01-20 05:00:00'),
(6, 'Oscar', 'Herrera', '3056789012', 'oscar.herrera@mail.com', '2025-01-22 05:00:00'),
(7, 'Miguel', 'Sánchez', '3067890123', 'miguel.sanchez@mail.com', '2025-01-25 05:00:00'),
(8, 'Luis', 'Martínez', '3078901234', 'luis.martinez@mail.com', '2025-01-28 05:00:00');

-- --------------------------------------------------------
-- Tabla: citas
-- --------------------------------------------------------
CREATE TABLE `citas` (
  `id_cita` INT(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` INT(11) NOT NULL,
  `id_barbero` INT(11) NOT NULL,
  `id_servicio` INT(11) NOT NULL,
  `fecha_cita` DATE NOT NULL,
  `hora_cita` TIME NOT NULL,
  `estado` ENUM('pendiente','confirmada','cancelada','realizada') DEFAULT 'pendiente',
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `citas` (`id_cita`, `id_cliente`, `id_barbero`, `id_servicio`, `fecha_cita`, `hora_cita`, `estado`, `fecha_creacion`) VALUES
(1, 1, 1, 1, '2025-02-01', '10:00:00', 'pendiente', '2025-08-29 20:16:49'),
(2, 2, 2, 2, '2025-02-01', '11:00:00', 'pendiente', '2025-08-29 20:16:49'),
(3, 3, 3, 3, '2025-02-01', '12:00:00', 'pendiente', '2025-08-29 20:16:49'),
(4, 4, 4, 4, '2025-02-02', '10:30:00', 'confirmada', '2025-08-29 20:16:49'),
(5, 5, 5, 5, '2025-02-02', '11:30:00', 'pendiente', '2025-08-29 20:16:49'),
(6, 6, 6, 6, '2025-02-02', '12:30:00', 'pendiente', '2025-08-29 20:16:49'),
(7, 7, 7, 7, '2025-02-03', '09:00:00', 'pendiente', '2025-08-29 20:16:49'),
(8, 8, 8, 8, '2025-02-03', '09:30:00', 'pendiente', '2025-08-29 20:16:49');

-- --------------------------------------------------------
-- Tabla: dashboard
-- --------------------------------------------------------
CREATE TABLE `dashboard` (
  `id_dashboard` INT(11) NOT NULL AUTO_INCREMENT,
  `id_barbero` INT(11) NOT NULL,
  `fecha_reporte` DATE NOT NULL,
  `horas_trabajadas` DECIMAL(5,2) DEFAULT 0.00,
  `clientes_atendidos` INT(11) DEFAULT 0,
  `ingresos_generados` DECIMAL(10,2) DEFAULT 0.00,
  `creado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dashboard`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `dashboard` VALUES
(1, 1, '2025-02-01', 8.00, 5, 75000.00, '2025-08-29 20:16:50');

-- --------------------------------------------------------
-- Tabla: eventos
-- --------------------------------------------------------
CREATE TABLE `eventos` (
  `id_evento` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(200) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `fecha_evento` DATE NOT NULL,
  `fecha_publicacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `publicado_por` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `eventos` VALUES
(1, 'Noche de Estilo', 'Evento de cortes gratuitos para clientes VIP.', '2025-02-01', '2025-08-29 20:16:49', NULL),
(2, 'Taller de Barba', 'Capacitación sobre técnicas de afeitado.', '2025-02-05', '2025-08-29 20:16:49', NULL),
(3, 'Competencia de Barberos', 'Concurso entre barberos locales.', '2025-02-10', '2025-08-29 20:16:49', NULL),
(4, 'Charla de Estilismo', 'Conferencia sobre tendencias actuales.', '2025-02-12', '2025-08-29 20:16:49', NULL),
(5, 'Show en Vivo', 'Demostración de cortes modernos.', '2025-02-15', '2025-08-29 20:16:49', NULL),
(6, 'Semana del Cliente', 'Promociones y regalos para clientes frecuentes.', '2025-02-18', '2025-08-29 20:16:49', NULL),
(7, 'Feria de Belleza', 'Participación en feria local de estética.', '2025-02-20', '2025-08-29 20:16:49', NULL),
(8, 'Curso Intensivo', 'Entrenamiento para nuevos barberos.', '2025-02-25', '2025-08-29 20:16:49', NULL);

-- --------------------------------------------------------
-- Tabla: galeria
-- --------------------------------------------------------
CREATE TABLE `galeria` (
  `id_galeria` INT(11) NOT NULL AUTO_INCREMENT,
  `img_galeria` VARCHAR(255) NOT NULL,
  `nombre_galeria` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_galeria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `galeria` VALUES
(1, 'corte7.png', 'Estilo de corte y barba 1'),
(2, 'corte8.png', 'Estilo de corte y barba 2'),
(3, 'corte9.png', 'Estilo de corte y barba 3'),
(4, 'corte10.png', 'Estilo de corte y barba 4'),
(5, 'corte11.png', 'Estilo de corte y barba 5'),
(6, 'corte12.png', 'Estilo de corte y barba 6'),
(7, 'corte13.png', 'Estilo de corte y barba 7'),
(8, 'corte14.png', 'Estilo de corte y barba 8'),
(9, 'corte15.png', 'Estilo de corte y barba 9');

-- --------------------------------------------------------
-- Tabla: ingresos
-- --------------------------------------------------------
CREATE TABLE `ingresos` (
  `id_ingreso` INT(11) NOT NULL AUTO_INCREMENT,
  `periodo` ENUM('semanal','mensual','anual') NOT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NOT NULL,
  `total_ingresos` DECIMAL(12,2) DEFAULT 0.00,
  `total_citas` INT(11) DEFAULT 0,
  `creado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ingreso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ingresos` VALUES
(1, 'semanal', '2025-02-01', '2025-02-07', 350000.00, 45, '2025-08-29 20:16:50');

-- --------------------------------------------------------
-- Tabla: noticias
-- --------------------------------------------------------
CREATE TABLE `noticias` (
  `id_noticia` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(200) NOT NULL,
  `contenido` TEXT NOT NULL,
  `fecha_publicacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `publicado_por` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_noticia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `noticias` VALUES
(1, 'Nueva promoción de cortes', 'Durante enero tenemos descuentos especiales en cortes clásicos. Aprovecha esta promoción para renovar tu estilo a un precio inigualable. Nuestros barberos profesionales te esperan para ofrecerte un servicio de calidad, rápido y con la mejor atención.', '2025-01-02 05:00:00', NULL),
(2, 'Concurso de estilo', 'Participa en nuestro Concurso de Estilo y demuestra tu creatividad con los cortes más originales. Los ganadores recibirán premios exclusivos y descuentos en próximos servicios. ¡No te pierdas esta oportunidad de brillar y mostrar tu talento en la barbería!', '2025-01-07 05:00:00', NULL),
(3, 'Apertura nocturna', '¡Ahora abrimos en horario nocturno! Ven a disfrutar de un corte o arreglo de barba después de tu jornada diaria. Nuestra barbería estará disponible en horarios extendidos para brindarte comodidad y el mejor servicio cuando más lo necesites.', '2025-01-12 05:00:00', NULL);

-- --------------------------------------------------------
-- Tabla: servicios
-- --------------------------------------------------------
CREATE TABLE `servicios` (
  `id_servicio` INT(11) NOT NULL AUTO_INCREMENT,
  `img_servicio` VARCHAR(255) DEFAULT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `precio` DECIMAL(10,2) DEFAULT NULL,
  `observacion` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `servicios` VALUES
(1, 'corte1.jpg', 'Corte de Cabello', 'Corte de cabello diseñado para que destaques.', 15000.00, NULL),
(2, 'corte de cabello y barba1.jpg', 'Corte de Cabello y Corte de Barba', 'Afeitado y Corte de Barba clásico, siempre resaltando tu mejor estilo.', 25000.00, NULL),
(3, 'corte de cabello y cejas.jpg', 'Corte de Cabello y Corte de Cejas', 'Siempre innovando y utilizando productos de excelente calidad.', 18000.00, NULL),
(4, 'corte de barba.jpg', 'Corte de Barba', 'Diferentes cortes y perfilación de barba, al mejor estilo de Área_51 la Super Barber.', 10000.00, NULL),
(5, 'corte de cejas.jpg', 'Corte de Cejas', 'Perfilación de cejas, que se adapta a tu estilo.', 5000.00, NULL),
(6, 'cerquillo.jpg', 'Cerquillo', 'Delineación de cortes, aplicando las mejores técnicas para resaltar tu estilo.', 5000.00, NULL),
(7, 'paquete premium.jpg', 'Paquete Premium', 'Combinación de corte de cabello, arreglo de barba, limpieza facial y masaje relajante.', 50000.00, NULL),
(8, 'keratina.jpg', 'Aplicación de Queratina', 'Renueva tu cabello con nuestra aplicación de queratina, dejándolo suave, brillante y manejable.', NULL, 'Costo según el largo del cabello');

-- --------------------------------------------------------
-- Tabla: testimonios
-- --------------------------------------------------------
CREATE TABLE `testimonios` (
  `id_testimonio` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `mensaje` TEXT NOT NULL,
  `img` VARCHAR(255) DEFAULT NULL,
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_testimonio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `testimonios` VALUES
(1, 'Juan Pérez', 'Excelente servicio, el ambiente es espectacular. ¡Súper recomendado!', 'yeisonBarber.png', '2025-08-30 02:05:36'),
(2, 'Andrés Gómez', 'El mejor corte que me han hecho, atención personalizada y profesionalismo.', 'yeisonBarber.png', '2025-08-30 02:05:36'),
(3, 'Carlos Martínez', 'Siempre salgo feliz con mi look, barberos muy capacitados.', 'yeisonBarber.png', '2025-08-30 02:05:36'),
(4, 'Luis Torres', 'La mejor barbería de la ciudad, ambiente único y cortes de primera.', 'yeisonBarber.png', '2025-08-30 02:05:36');

-- --------------------------------------------------------
-- Tabla: tiktok
-- --------------------------------------------------------
-- --------------------------------------------------------
-- Tabla: tiktok
-- --------------------------------------------------------
CREATE TABLE tiktok (
  id_tiktok INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  url VARCHAR(255) NOT NULL,
  video_id VARCHAR(50) NOT NULL,
  descripcion VARCHAR(255) DEFAULT NULL,
  fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos para la tabla tiktok
INSERT INTO tiktok (id_tiktok, url, video_id, descripcion, fecha_registro) VALUES
(1, 'https://www.tiktok.com/@ysarmiento.barber/video/7492250638236110086', '7492250638236110086', NULL, '2025-08-30 01:48:49'),
(2, 'https://www.tiktok.com/@ysarmiento.barber/video/7493208342282767621', '7493208342282767621', NULL, '2025-08-30 01:48:49'),
(3, 'https://www.tiktok.com/@ysarmiento.barber/video/7494847153395813637', '7494847153395813637', NULL, '2025-08-30 01:48:49');

-- --------------------------------------------------------
-- Tabla: videos
-- --------------------------------------------------------
CREATE TABLE videos (
  id_video INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  url VARCHAR(500) NOT NULL,
  fecha_publicacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  publicado_por INT(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos para la tabla videos
INSERT INTO videos (id_video, titulo, url, fecha_publicacion, publicado_por) VALUES
(1, '10 Cortes de moda 2025', 'https://youtu.be/rugYY0WMlj0?si=vz7wB3rEc3L35Q0u', '2025-01-05 05:00:00', NULL),
(2, 'Como Hacer un Desvanecido en V', 'https://youtu.be/rpdpu_Ktnkw?si=AL0CTIwJccelfPB5', '2025-05-07 05:00:00', NULL),
(3, 'El arte del cuidado personal', 'https://youtu.be/7_lQ_HQnMwY?si=JsMpORA77jeyLrLi', '2025-08-10 05:00:00', NULL);