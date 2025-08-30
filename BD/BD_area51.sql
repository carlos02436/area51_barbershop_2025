-- Crear la base de datos
CREATE DATABASE area51_barberia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE area51_barberia;

-- =========================
-- TABLA CLIENTES
-- =========================
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(150) UNIQUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLA ADMINISTRADORES
-- =========================
CREATE TABLE administradores (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nombre   VARCHAR(100) NOT NULL,
    usuario  VARCHAR(50)  NOT NULL UNIQUE,
    email    VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trigger para limitar a 3 administradores
DELIMITER $$
CREATE TRIGGER limite_administradores
BEFORE INSERT ON administradores
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

-- =========================
-- TABLA BARBEROS
-- =========================
CREATE TABLE barberos (
    id_barbero INT AUTO_INCREMENT PRIMARY KEY,
    img_barberos VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    especialidad TEXT NOT NULL,
    telefono VARCHAR(20),
    fecha_contratacion DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- TABLA NOTICIAS
-- =========================
CREATE TABLE noticias (
    id_noticia INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    contenido TEXT NOT NULL,
    fecha_publicacion DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- TABLA VIDEOS
-- =========================
CREATE TABLE videos (
    id_video INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    url VARCHAR(500) NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    publicado_por INT
);

-- =========================
-- TABLA EVENTOS
-- =========================
CREATE TABLE eventos (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_evento DATE NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    publicado_por INT
);

-- =========================
-- TABLA SERVICIOS
-- =========================
CREATE TABLE servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    img_servicio VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NULL,
    observacion VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- TABLA CITAS
-- =========================
CREATE TABLE citas (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_barbero INT NOT NULL,
    id_servicio INT NOT NULL,
    fecha_cita DATE NOT NULL,
    hora_cita TIME NOT NULL,
    estado ENUM('pendiente','confirmada','cancelada','realizada') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLA GALERÍA
-- ============================================
CREATE TABLE galeria (
    id_galeria INT AUTO_INCREMENT PRIMARY KEY,
    img_galeria VARCHAR(255) NOT NULL,
    nombre_galeria VARCHAR(100) NOT NULL
);

-- ============================================
-- TABLA GALERÍA
-- ============================================
INSERT INTO galeria (img_galeria, nombre_galeria) VALUES
('corte7.png', 'Estilo de corte y barba 1'),
('corte8.png', 'Estilo de corte y barba 2'),
('corte9.png', 'Estilo de corte y barba 3'),
('corte10.png', 'Estilo de corte y barba 4'),
('corte11.png', 'Estilo de corte y barba 5'),
('corte12.png', 'Estilo de corte y barba 6'),
('corte13.png', 'Estilo de corte y barba 7'),
('corte14.png', 'Estilo de corte y barba 8'),
('corte15.png', 'Estilo de corte y barba 9');

-- ============================================
-- REGISTROS CLIENTES
-- ============================================
INSERT INTO clientes (nombre, apellido, telefono, correo, fecha_registro) VALUES
('Carlos', 'Pérez', '3001234567', 'carlos.perez@mail.com', '2025-01-05'),
('Andrés', 'Gómez', '3012345678', 'andres.gomez@mail.com', '2025-01-10'),
('Juan', 'Rodríguez', '3023456789', 'juan.rodriguez@mail.com', '2025-01-12'),
('Felipe', 'Ramírez', '3034567890', 'felipe.ramirez@mail.com', '2025-01-15'),
('David', 'Torres', '3045678901', 'david.torres@mail.com', '2025-01-20'),
('Oscar', 'Herrera', '3056789012', 'oscar.herrera@mail.com', '2025-01-22'),
('Miguel', 'Sánchez', '3067890123', 'miguel.sanchez@mail.com', '2025-01-25'),
('Luis', 'Martínez', '3078901234', 'luis.martinez@mail.com', '2025-01-28');

-- ============================================
-- REGISTROS ADMINISTRADORES
-- ============================================
INSERT INTO administradores (nombre, usuario, email, password) VALUES
('Carlos Parra',     'cparra',      'carlos.parra@example.com',    '12345'),
('Yeison Sarmiento', 'ysarmiento',  'yeison.sarmiento@example.com','12345'),
('Gisell',           'gisell',      'gisell@example.com',          '12345');

-- ============================================
-- REGISTROS BARBEROS
-- ============================================
INSERT INTO barberos (img_barberos, nombre, especialidad, telefono, fecha_contratacion) VALUES
('Yisel_herrera.png', 'Yisel Herrera', 'Es la encargada de atención al cliente, siempre amable y dispuesta a ayudar a cada visitante a sentirse como en casa. Su trato cercano y profesional la convierte en un pilar fundamental para la experiencia de quienes visitan la barbería.', '3101234567', '2024-12-01'),
('yeisonBarber.png', 'Yeison Sarmiento', 'Es un barbero excelente, con gran precisión en los cortes y un estilo moderno que encanta a los clientes. Su dedicación y creatividad hacen que cada visita sea una experiencia única y satisfactoria.', '3112345678', '2024-12-05'),
('Rafael_Barrios.png', 'Rafael Barrios', 'Destaca por su técnica impecable y su pasión por ofrecer siempre un corte de calidad superior. Su compromiso con la perfección lo ha convertido en uno de los barberos más confiables y solicitados por los clientes.', '3123456789', '2024-12-10'),
('Rafael_Jaime.png', 'Rafael Jaime', 'Es reconocido por su estilo detallista, garantizando siempre un acabado elegante y profesional. Su habilidad para adaptarse a las tendencias lo hace sobresalir como un referente en la barbería.', '3134567890', '2024-12-15'),
('Samuel_Martinez.png', 'Samuel Martínez', 'Es un barbero con gran creatividad, logrando cortes únicos y personalizados que dejan huella en cada cliente. Su talento y carisma generan confianza, lo que hace que siempre sea altamente recomendado.', '3145678901', '2024-12-18');

-- ============================================
-- REGISTROS SERVICIOS
-- ============================================
INSERT INTO servicios (img_servicio, nombre, descripcion, precio, observacion) VALUES
('corte1.jpg', 'Corte de Cabello', 'Corte de cabello diseñado para que destaques.', 15000.00, NULL),
('corte de cabello y barba1.jpg', 'Corte de Cabello y Corte de Barba', 'Afeitado y corte de barba clásico, resaltando tu mejor estilo.', 25000.00, NULL),
('corte de cabello y cejas.jpg', 'Corte de Cabello y Corte de Cejas', 'Corte de cabello con perfilación de cejas.', 18000.00, NULL),
('corte de barba.jpg', 'Corte de Barba', 'Diferentes cortes y perfilación de barba.', 10000.00, NULL),
('corte de cejas.jpg', 'Corte de Cejas', 'Perfilación de cejas adaptada a tu estilo.', 5000.00, NULL),
('cerquillo.jpg', 'Cerquillo', 'Delineación de cortes para resaltar tu estilo.', 5000.00, NULL),
('paquete premium.jpg', 'Paquete Premium', 'Corte de cabello + barba + limpieza facial + masaje relajante.', 50000.00, NULL),
('keratina.jpg', 'Aplicación de Queratina', 'Tratamiento para suavidad, brillo y manejabilidad del cabello.', NULL, 'Costo según el largo del cabello');

-- ============================================
-- REGISTROS NOTICIAS
-- ============================================
INSERT INTO noticias (titulo, contenido, fecha_publicacion) VALUES
('Nueva promoción de cortes', 
 'Durante enero tenemos descuentos especiales en cortes clásicos. Aprovecha esta promoción para renovar tu estilo a un precio inigualable. Nuestros barberos profesionales te esperan para ofrecerte un servicio de calidad, rápido y con la mejor atención.', 
 '2025-01-05'),

('Concurso de estilo', 
 'Participa en nuestro Concurso de Estilo y demuestra tu creatividad con los cortes más originales. Los ganadores recibirán premios exclusivos y descuentos en próximos servicios. ¡No te pierdas esta oportunidad de brillar y mostrar tu talento en la barbería!', 
 '2025-01-15'),

('Apertura nocturna', 
 '¡Ahora abrimos en horario nocturno! Ven a disfrutar de un corte o arreglo de barba después de tu jornada diaria. Nuestra barbería estará disponible en horarios extendidos para brindarte comodidad y el mejor servicio cuando más lo necesites.', 
 '2025-01-20');

-- ============================================
-- REGISTROS VIDEOS
-- ============================================
INSERT INTO videos (titulo, url, fecha_publicacion) VALUES
('10 Cortes de moda 2025', 'https://youtu.be/rugYY0WMlj0?si=vz7wB3rEc3L35Q0u', '2025-01-05'),
('Como Hacer un Desvanecido en V', 'https://youtu.be/rpdpu_Ktnkw?si=AL0CTIwJccelfPB5', '2025-05-07'),
('El arte del cuidado personal', 'https://youtu.be/7_lQ_HQnMwY?si=JsMpORA77jeyLrLi', '2025-08-10');

-- ============================================
-- REGISTROS EVENTOS
-- ============================================
INSERT INTO eventos (titulo, descripcion, fecha_evento) VALUES
('Noche de Estilo', 'Evento de cortes gratuitos para clientes VIP.', '2025-02-01'),
('Taller de Barba', 'Capacitación sobre técnicas de afeitado.', '2025-02-05'),
('Competencia de Barberos', 'Concurso entre barberos locales.', '2025-02-10'),
('Charla de Estilismo', 'Conferencia sobre tendencias actuales.', '2025-02-12'),
('Show en Vivo', 'Demostración de cortes modernos.', '2025-02-15'),
('Semana del Cliente', 'Promociones y regalos para clientes frecuentes.', '2025-02-18'),
('Feria de Belleza', 'Participación en feria local de estética.', '2025-02-20'),
('Curso Intensivo', 'Entrenamiento para nuevos barberos.', '2025-02-25');

-- ============================================
-- REGISTROS CITAS
-- ============================================
INSERT INTO citas (id_cliente, id_barbero, id_servicio, fecha_cita, hora_cita, estado) VALUES
(1, 1, 1, '2025-02-01', '10:00:00', 'pendiente'),
(2, 2, 2, '2025-02-01', '11:00:00', 'pendiente'),
(3, 3, 3, '2025-02-01', '12:00:00', 'pendiente'),
(4, 4, 4, '2025-02-02', '10:30:00', 'confirmada'),
(5, 5, 5, '2025-02-02', '11:30:00', 'pendiente'),
(6, 6, 6, '2025-02-02', '12:30:00', 'pendiente'),
(7, 7, 7, '2025-02-03', '09:00:00', 'pendiente'),
(8, 8, 8, '2025-02-03', '09:30:00', 'pendiente');

-- ============================================
-- LUEGO DE CREAR LA BD, ANEXAR:
-- ============================================
USE area51_barberia;

-- =========================
-- 1. Crear tabla INGRESOS
-- =========================
CREATE TABLE IF NOT EXISTS ingresos (
    id_ingreso INT AUTO_INCREMENT PRIMARY KEY,
    periodo ENUM('semanal','mensual','anual') NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    total_ingresos DECIMAL(12,2) DEFAULT 0,
    total_citas INT DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- 2. Crear tabla DASHBOARD
-- =========================
CREATE TABLE IF NOT EXISTS dashboard (
    id_dashboard INT AUTO_INCREMENT PRIMARY KEY,
    id_barbero INT NOT NULL,
    fecha_reporte DATE NOT NULL,
    horas_trabajadas DECIMAL(5,2) DEFAULT 0,
    clientes_atendidos INT DEFAULT 0,
    ingresos_generados DECIMAL(10,2) DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_barbero) REFERENCES barberos(id_barbero)
);

-- =========================
-- 3. Insertar un barbero de prueba (NECESARIO para FK)
-- =========================
INSERT INTO barberos (nombre, especialidad, telefono, fecha_contratacion)
VALUES ('Carlos Ruiz', 'Cortes modernos', '3001234567', '2025-01-15');

-- =========================
-- 4. Insertar registros en DASHBOARD
-- =========================
-- Usa el id_barbero real (verifícalo con SELECT * FROM barberos;)
INSERT INTO dashboard (id_barbero, fecha_reporte, horas_trabajadas, clientes_atendidos, ingresos_generados)
VALUES (1, '2025-02-01', 8.0, 5, 75000.00);

-- =========================
-- 5. Insertar registros en INGRESOS
-- =========================
INSERT INTO ingresos (periodo, fecha_inicio, fecha_fin, total_ingresos, total_citas)
VALUES ('semanal', '2025-02-01', '2025-02-07', 350000.00, 45);
