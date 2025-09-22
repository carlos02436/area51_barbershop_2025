-- ==========================================
-- Crear base de datos
-- ==========================================
DROP DATABASE IF EXISTS `area51_barbershop_2025`;
CREATE DATABASE `area51_barbershop_2025` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `area51_barbershop_2025`;

-- ===================================================
-- Tabla: administradores
-- ===================================================
CREATE TABLE `administradores` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `img_admin` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `administradores` (`id_admin`, `img_admin`, `nombre`, `usuario`, `email`, `password`, `creado_en`) VALUES
(2, 'yeisonBarber.png', 'Yeison Sarmiento', 'ysarmiento', 'cparra02436@gmail.com', '12345', '2025-08-30 01:16:49'),
(3, 'Yisel_herrera.png', 'Yisel Herrera', 'yiselHr', 'yisel@example.com', '12345', '2025-08-30 01:16:49');

-- Trigger para límite de administradores
DELIMITER $$
CREATE TRIGGER `limite_administradores` BEFORE INSERT ON `administradores` FOR EACH ROW
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM administradores;
    IF total >= 3 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se pueden registrar más de 3 administradores.';
    END IF;
END$$
DELIMITER ;

-- ===================================================
-- Tabla: barberos
-- ===================================================
CREATE TABLE `barberos` (
  `id_barbero` int(11) NOT NULL AUTO_INCREMENT,
  `img_barberos` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `especialidad` varchar(500) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_contratacion` date DEFAULT NULL,
  PRIMARY KEY (`id_barbero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `barberos` (`id_barbero`, `img_barberos`, `nombre`, `especialidad`, `telefono`, `email`, `fecha_contratacion`) VALUES
(1, 'yeisonbarber.png', 'Yeison Sarmiento', 'Barbero creativo y moderno', '573124732236', 'yeison.sarmiento@area51.com', '2024-12-05'),
(2, 'Rafael_Barrios.png', 'Rafael Barrios', 'Técnica impecable y compromiso', '573124732236', 'rafael.barrios@area51.com', '2024-12-10'),
(3, 'Rafael_Jaime.png', 'Rafael Jaime', 'Estilo detallista y elegante', '573124732236', 'rafael.jaime@area51.com', '2024-12-15'),
(4, 'Samuel_Martinez.png', 'Samuel Martínez', 'Creativo y personalizado', '573124732236', 'samuel.martinez@area51.com', '2024-12-18');

-- Triggers para agregar prefijo 57 al teléfono
DELIMITER $$
CREATE TRIGGER `before_insert_barberos` BEFORE INSERT ON `barberos` FOR EACH ROW
BEGIN
    IF LEFT(NEW.telefono, 2) <> '57' THEN
        SET NEW.telefono = CONCAT('57', NEW.telefono);
    END IF;
END$$

CREATE TRIGGER `before_update_barberos` BEFORE UPDATE ON `barberos` FOR EACH ROW
BEGIN
    IF LEFT(NEW.telefono, 2) <> '57' THEN
        SET NEW.telefono = CONCAT('57', NEW.telefono);
    END IF;
END$$
DELIMITER ;

-- ===================================================
-- Tabla: clientes
-- ===================================================
CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: servicios
-- ===================================================
CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `img_servicio` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: citas
-- ===================================================
CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_barbero` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `img_servicio` varchar(255) DEFAULT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada','realizada') DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_cita`),
  KEY `fk_citas_clientes` (`id_cliente`),
  KEY `fk_citas_barberos` (`id_barbero`),
  KEY `fk_citas_servicios` (`id_servicio`),
  CONSTRAINT `fk_citas_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_citas_barberos` FOREIGN KEY (`id_barbero`) REFERENCES `barberos` (`id_barbero`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_citas_servicios` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: dashboard
-- ===================================================
CREATE TABLE `dashboard` (
  `id_dashboard` int(11) NOT NULL AUTO_INCREMENT,
  `id_barbero` int(11) NOT NULL,
  `fecha_reporte` date NOT NULL,
  `horas_trabajadas` decimal(5,2) DEFAULT 0.00,
  `clientes_atendidos` int(11) DEFAULT 0,
  `ingresos_generados` decimal(10,2) DEFAULT 0.00,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_dashboard`),
  KEY `fk_dashboard_barberos` (`id_barbero`),
  CONSTRAINT `fk_dashboard_barberos` FOREIGN KEY (`id_barbero`) REFERENCES `barberos` (`id_barbero`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: noticias
-- ===================================================
CREATE TABLE `noticias` (
  `id_noticia` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `publicado_por` int(11) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (`id_noticia`),
  KEY `fk_noticias_publicado_por` (`publicado_por`),
  CONSTRAINT `fk_noticias_publicado_por` FOREIGN KEY (`publicado_por`) REFERENCES `administradores` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: galeria
-- ===================================================
CREATE TABLE `galeria` (
  `id_galeria` int(11) NOT NULL AUTO_INCREMENT,
  `img_galeria` varchar(255) NOT NULL,
  `nombre_galeria` varchar(100) NOT NULL,
  PRIMARY KEY (`id_galeria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: testimonios
-- ===================================================
CREATE TABLE `testimonios` (
  `id_testimonio` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_testimonio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: tiktok
-- ===================================================
CREATE TABLE `tiktok` (
  `id_tiktok` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `video_id` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `publicado_por` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id_tiktok`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: videos
-- ===================================================
CREATE TABLE `videos` (
  `id_video` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `publicado_por` varchar(100) NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (`id_video`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: pqrs
-- ===================================================
CREATE TABLE `pqrs` (
  `id_pqrs` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `tipo` enum('Petición','Queja','Reclamo','Sugerencia') NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Pendiente','En Proceso','Resuelto') NOT NULL DEFAULT 'Pendiente',
  PRIMARY KEY (`id_pqrs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: ingresos
-- ===================================================
CREATE TABLE `ingresos` (
  `id_ingreso` int(11) NOT NULL AUTO_INCREMENT,
  `periodo` enum('diario','semanal','mensual','anual') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `total_ingresos` decimal(12,2) DEFAULT 0.00,
  `total_citas` int(11) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_ingreso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Tabla: eventos
-- ===================================================
CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_evento` date NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `publicado_por` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_evento`),
  KEY `fk_eventos_admin` (`publicado_por`),
  CONSTRAINT `fk_eventos_admin` FOREIGN KEY (`publicado_por`) REFERENCES `administradores` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================
-- Evento: actualizar ingresos diarios
-- ===================================================
DELIMITER $$
CREATE EVENT IF NOT EXISTS `actualizar_ingresos_diarios`
ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP
DO
BEGIN
    DECLARE inicio DATE;
    DECLARE fin DATE;
    SET inicio = CURDATE();
    SET fin = CURDATE();

    IF NOT EXISTS (
        SELECT 1 FROM ingresos WHERE fecha_inicio = inicio AND fecha_fin = fin AND periodo = 'diario'
    ) THEN
        INSERT INTO ingresos (periodo, fecha_inicio, fecha_fin, total_ingresos, total_citas, creado_en)
        SELECT 
            'diario',
            inicio,
            fin,
            COALESCE(SUM(s.precio), 0),
            COALESCE(COUNT(c.id_cita), 0),
            NOW()
        FROM citas c
        JOIN servicios s ON c.id_servicio = s.id_servicio
        WHERE c.fecha_cita = inicio
          AND c.estado = 'confirmada';
    END IF;
END$$
DELIMITER ;