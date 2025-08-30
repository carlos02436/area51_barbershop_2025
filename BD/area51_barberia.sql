-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-08-2025 a las 04:08:46
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `area51_barberia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id_admin` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id_admin`, `nombre`, `usuario`, `email`, `password`, `creado_en`) VALUES
(1, 'Carlos Parra', 'cparra', 'carlos.parra@example.com', '12345', '2025-08-29 20:16:49'),
(2, 'Yeison Sarmiento', 'ysarmiento', 'yeison.sarmiento@example.com', '12345', '2025-08-29 20:16:49'),
(3, 'Gisell', 'gisell', 'gisell@example.com', '12345', '2025-08-29 20:16:49');

--
-- Disparadores `administradores`
--
DELIMITER $$
CREATE TRIGGER `limite_administradores` BEFORE INSERT ON `administradores` FOR EACH ROW BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM administradores;
    IF total >= 3 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se pueden registrar más de 3 administradores.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `barberos`
--

CREATE TABLE `barberos` (
  `id_barbero` int(11) NOT NULL,
  `img_barberos` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `especialidad` varchar(500) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_contratacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `barberos`
--

INSERT INTO `barberos` (`id_barbero`, `img_barberos`, `nombre`, `especialidad`, `telefono`, `fecha_contratacion`) VALUES
(1, 'Yisel_herrera.png', 'Yisel Herrera', 'Es la encargada de atención al cliente, siempre amable y dispuesta a ayudar a cada visitante a sentirse como en casa. Su trato cercano y profesional la convierte en un pilar fundamental para la experiencia de quienes visitan la barbería.', '3101234567', '2024-12-01'),
(2, 'yeisonBarber.png', 'Yeison Sarmiento', 'Es un barbero excelente, con gran precisión en los cortes y un estilo moderno que encanta a los clientes. Su dedicación y creatividad hacen que cada visita sea una experiencia única y satisfactoria.', '3112345678', '2024-12-05'),
(3, 'Rafael_Barrios.png', 'Rafael Barrios', 'Destaca por su técnica impecable y su pasión por ofrecer siempre un corte de calidad superior. Su compromiso con la perfección lo ha convertido en uno de los barberos más confiables y solicitados por los clientes.', '3123456789', '2024-12-10'),
(4, 'Rafael_Jaime.png', 'Rafael Jaime', 'Es reconocido por su estilo detallista, garantizando siempre un acabado elegante y profesional. Su habilidad para adaptarse a las tendencias lo hace sobresalir como un referente en la barbería.', '3134567890', '2024-12-15'),
(5, 'Samuel_Martinez.png', 'Samuel Martínez', 'Es un barbero con gran creatividad, logrando cortes únicos y personalizados que dejan huella en cada cliente. Su talento y carisma generan confianza, lo que hace que siempre sea altamente recomendado.', '3145678901', '2024-12-18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_barbero` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada','realizada') DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

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

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

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

--
-- Estructura de tabla para la tabla `dashboard`
--

CREATE TABLE `dashboard` (
  `id_dashboard` int(11) NOT NULL,
  `id_barbero` int(11) NOT NULL,
  `fecha_reporte` date NOT NULL,
  `horas_trabajadas` decimal(5,2) DEFAULT 0.00,
  `clientes_atendidos` int(11) DEFAULT 0,
  `ingresos_generados` decimal(10,2) DEFAULT 0.00,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dashboard`
--

INSERT INTO `dashboard` (`id_dashboard`, `id_barbero`, `fecha_reporte`, `horas_trabajadas`, `clientes_atendidos`, `ingresos_generados`, `creado_en`) VALUES
(1, 1, '2025-02-01', 8.00, 5, 75000.00, '2025-08-29 20:16:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_evento` date NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `publicado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id_evento`, `titulo`, `descripcion`, `fecha_evento`, `fecha_publicacion`, `publicado_por`) VALUES
(1, 'Noche de Estilo', 'Evento de cortes gratuitos para clientes VIP.', '2025-02-01', '2025-08-29 20:16:49', NULL),
(2, 'Taller de Barba', 'Capacitación sobre técnicas de afeitado.', '2025-02-05', '2025-08-29 20:16:49', NULL),
(3, 'Competencia de Barberos', 'Concurso entre barberos locales.', '2025-02-10', '2025-08-29 20:16:49', NULL),
(4, 'Charla de Estilismo', 'Conferencia sobre tendencias actuales.', '2025-02-12', '2025-08-29 20:16:49', NULL),
(5, 'Show en Vivo', 'Demostración de cortes modernos.', '2025-02-15', '2025-08-29 20:16:49', NULL),
(6, 'Semana del Cliente', 'Promociones y regalos para clientes frecuentes.', '2025-02-18', '2025-08-29 20:16:49', NULL),
(7, 'Feria de Belleza', 'Participación en feria local de estética.', '2025-02-20', '2025-08-29 20:16:49', NULL),
(8, 'Curso Intensivo', 'Entrenamiento para nuevos barberos.', '2025-02-25', '2025-08-29 20:16:49', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria`
--

CREATE TABLE `galeria` (
  `id_galeria` int(11) NOT NULL,
  `img_galeria` varchar(255) NOT NULL,
  `nombre_galeria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `galeria`
--

INSERT INTO `galeria` (`id_galeria`, `img_galeria`, `nombre_galeria`) VALUES
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

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id_ingreso` int(11) NOT NULL,
  `periodo` enum('semanal','mensual','anual') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `total_ingresos` decimal(12,2) DEFAULT 0.00,
  `total_citas` int(11) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id_ingreso`, `periodo`, `fecha_inicio`, `fecha_fin`, `total_ingresos`, `total_citas`, `creado_en`) VALUES
(1, 'semanal', '2025-02-01', '2025-02-07', 350000.00, 45, '2025-08-29 20:16:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id_noticia` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `publicado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id_noticia`, `titulo`, `contenido`, `fecha_publicacion`, `publicado_por`) VALUES
(1, 'Nueva promoción de cortes', 'Durante enero tenemos descuentos especiales en cortes clásicos. Aprovecha esta promoción para renovar tu estilo a un precio inigualable. Nuestros barberos profesionales te esperan para ofrecerte un servicio de calidad, rápido y con la mejor atención.', '2025-01-02 05:00:00', NULL),
(2, 'Concurso de estilo', 'Participa en nuestro Concurso de Estilo y demuestra tu creatividad con los cortes más originales. Los ganadores recibirán premios exclusivos y descuentos en próximos servicios. ¡No te pierdas esta oportunidad de brillar y mostrar tu talento en la barbería!', '2025-01-07 05:00:00', NULL),
(3, 'Apertura nocturna', '¡Ahora abrimos en horario nocturno! Ven a disfrutar de un corte o arreglo de barba después de tu jornada diaria. Nuestra barbería estará disponible en horarios extendidos para brindarte comodidad y el mejor servicio cuando más lo necesites.', '2025-01-12 05:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `img_servicio` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `img_servicio`, `nombre`, `descripcion`, `precio`, `observacion`) VALUES
(1, 'corte1.jpg', 'Corte de Cabello', 'Corte de cabello diseñado para que destaques.', 15000.00, NULL),
(2, 'corte de cabello y barba1.jpg', 'Corte de Cabello y Corte de Barba', 'Afeitado y Corte de Barba clásico, siempre resaltando tu mejor estilo.', 25000.00, NULL),
(3, 'corte de cabello y cejas.jpg', 'Corte de Cabello y Corte de Cejas', 'Siempre innovando y utilizando productos de excelente calidad.', 18000.00, NULL),
(4, 'corte de barba.jpg', 'Corte de Barba', 'Diferentes cortes y perfilación de barba, al mejor estilo de Área_51 la Super Barber.', 10000.00, NULL),
(5, 'corte de cejas.jpg', 'Corte de Cejas', 'Perfilación de cejas, que se adapta a tu estilo.', 5000.00, NULL),
(6, 'cerquillo.jpg', 'Cerquillo', 'Delineación de cortes, aplicando las mejores técnicas para resaltar tu estilo.', 5000.00, NULL),
(7, 'paquete premium.jpg', 'Paquete Premium', 'Combinación de corte de cabello, arreglo de barba, limpieza facial y masaje relajante.', 50000.00, NULL),
(8, 'keratina.jpg', 'Aplicación de Queratina', 'Renueva tu cabello con nuestra aplicación de queratina, dejándolo suave, brillante y manejable.', NULL, 'Costo según el largo del cabello');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonios`
--

CREATE TABLE `testimonios` (
  `id_testimonio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `testimonios`
--

INSERT INTO `testimonios` (`id_testimonio`, `nombre`, `mensaje`, `img`, `fecha_registro`) VALUES
(1, 'Juan Pérez', 'Excelente servicio, el ambiente es espectacular. ¡Súper recomendado!', 'yeisonBarber.png', '2025-08-30 02:05:36'),
(2, 'Andrés Gómez', 'El mejor corte que me han hecho, atención personalizada y profesionalismo.', 'yeisonBarber.png', '2025-08-30 02:05:36'),
(3, 'Camilo Torres', 'Un lugar increíble donde te hacen sentir como en casa. Muy recomendado.', 'yeisonBarber.png', '2025-08-30 02:05:36'),
(4, 'Sebastián Martínez', 'Cortes modernos y el personal súper amable. Sin duda volveré.', 'yeisonBarber.png', '2025-08-30 02:05:36'),
(5, 'Diego Herrera', 'Muy buena experiencia, me encantó la asesoría sobre el estilo que más me convenía.', 'yeisonBarber.png', '2025-08-30 02:05:36'),
(6, 'Luis Ramírez', 'Profesionales de verdad, te sientes en manos expertas desde el primer momento.', 'yeisonBarber.png', '2025-08-30 02:05:36'),
(7, 'Ricardo Castaño', 'Simplemente los mejores, me devolvieron la confianza en los barberos.', 'yeisonBarber.png', '2025-08-30 02:05:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiktok`
--

CREATE TABLE `tiktok` (
  `id_tiktok` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `video_id` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiktok`
--

INSERT INTO `tiktok` (`id_tiktok`, `url`, `video_id`, `descripcion`, `fecha_registro`) VALUES
(1, 'https://www.tiktok.com/@ysarmiento.barber/video/7492250638236110086', '7492250638236110086', NULL, '2025-08-30 01:48:49'),
(2, 'https://www.tiktok.com/@ysarmiento.barber/video/7493208342282767621', '7493208342282767621', NULL, '2025-08-30 01:48:49'),
(3, 'https://www.tiktok.com/@ysarmiento.barber/video/7494847153395813637', '7494847153395813637', NULL, '2025-08-30 01:48:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `videos`
--

CREATE TABLE `videos` (
  `id_video` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `url` varchar(500) NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `publicado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `videos`
--

INSERT INTO `videos` (`id_video`, `titulo`, `url`, `fecha_publicacion`, `publicado_por`) VALUES
(1, '10 Cortes de moda 2025', 'https://youtu.be/rugYY0WMlj0?si=vz7wB3rEc3L35Q0u', '2025-01-05 05:00:00', NULL),
(2, 'Como Hacer un Desvanecido en V', 'https://youtu.be/rpdpu_Ktnkw?si=AL0CTIwJccelfPB5', '2025-05-07 05:00:00', NULL),
(3, 'El arte del cuidado personal', 'https://youtu.be/7_lQ_HQnMwY?si=JsMpORA77jeyLrLi', '2025-08-10 05:00:00', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `barberos`
--
ALTER TABLE `barberos`
  ADD PRIMARY KEY (`id_barbero`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `dashboard`
--
ALTER TABLE `dashboard`
  ADD PRIMARY KEY (`id_dashboard`),
  ADD KEY `id_barbero` (`id_barbero`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`);

--
-- Indices de la tabla `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id_galeria`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id_ingreso`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id_noticia`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  ADD PRIMARY KEY (`id_testimonio`);

--
-- Indices de la tabla `tiktok`
--
ALTER TABLE `tiktok`
  ADD PRIMARY KEY (`id_tiktok`);

--
-- Indices de la tabla `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id_video`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `barberos`
--
ALTER TABLE `barberos`
  MODIFY `id_barbero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `dashboard`
--
ALTER TABLE `dashboard`
  MODIFY `id_dashboard` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id_galeria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id_noticia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  MODIFY `id_testimonio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tiktok`
--
ALTER TABLE `tiktok`
  MODIFY `id_tiktok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `videos`
--
ALTER TABLE `videos`
  MODIFY `id_video` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dashboard`
--
ALTER TABLE `dashboard`
  ADD CONSTRAINT `dashboard_ibfk_1` FOREIGN KEY (`id_barbero`) REFERENCES `barberos` (`id_barbero`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
