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
    img_barberos	varchar(255),
    nombre VARCHAR(100) NOT NULL,
    especialidad VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    fecha_contratacion DATE
);

-- =========================
-- TABLA NOTICIAS
-- =========================
CREATE TABLE noticias (
    id_noticia INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    contenido TEXT NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    publicado_por INT
);

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
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NULL,
    observacion VARCHAR(255) NULL
);

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
('', 'José Ramírez', 'Cortes clásicos', '3101234567', '2024-12-01'),
('', 'Mario López', 'Degradados', '3112345678', '2024-12-05'),
('', 'Fernando Ruiz', 'Barbas y afeitados', '3123456789', '2024-12-10'),
('', 'Pedro Méndez', 'Cortes modernos', '3134567890', '2024-12-15'),
('', 'Ricardo Silva', 'Tinturas y diseños', '3145678901', '2024-12-18'),
('', 'Alejandro Torres', 'Peinados y estilo', '3156789012', '2024-12-20'),
('', 'Héctor Vargas', 'Cortes rápidos', '3167890123', '2024-12-22'),
('', 'Camilo Peña', 'Cortes artísticos', '3178901234', '2024-12-25');

-- ============================================
-- REGISTROS SERVICIOS
-- ============================================
INSERT INTO servicios (nombre, descripcion, precio, observacion) VALUES
('Corte de Cabello', 'Corte de cabello diseñado para que destaques.', 15000.00, NULL),
('Corte de Cabello y Corte de Barba', 'Afeitado y corte de barba clásico, resaltando tu mejor estilo.', 25000.00, NULL),
('Corte de Cabello y Corte de Cejas', 'Corte de cabello con perfilación de cejas.', 18000.00, NULL),
('Corte de Barba', 'Diferentes cortes y perfilación de barba.', 10000.00, NULL),
('Corte de Cejas', 'Perfilación de cejas adaptada a tu estilo.', 5000.00, NULL),
('Cerquillo', 'Delineación de cortes para resaltar tu estilo.', 5000.00, NULL),
('Paquete Premium', 'Corte de cabello + barba + limpieza facial + masaje relajante.', 50000.00, NULL),
('Aplicación de Queratina', 'Tratamiento para suavidad, brillo y manejabilidad del cabello.', NULL, 'Costo según el largo del cabello');

-- ============================================
-- REGISTROS NOTICIAS
-- ============================================
INSERT INTO noticias (titulo, contenido, fecha_publicacion) VALUES
('Nueva promoción de cortes', 'Durante enero tenemos descuentos en cortes clásicos.', '2025-01-02'),
('Concurso de estilo', 'Participa en nuestro concurso de peinados y gana premios.', '2025-01-07'),
('Apertura nocturna', 'Abriremos hasta las 10pm los viernes y sábados.', '2025-01-12'),
('Barberos invitados', 'Este mes tendremos barberos internacionales.', '2025-01-15'),
('Nuevo servicio disponible', 'Ya contamos con tratamientos capilares.', '2025-01-18'),
('Día del hombre', 'Promociones especiales por el día del hombre.', '2025-01-20'),
('Capacitación continua', 'Nuestros barberos se capacitan en nuevas técnicas.', '2025-01-25'),
('Premio a la mejor barbería', 'Ganamos reconocimiento local como mejor barbería.', '2025-01-28');

-- ============================================
-- REGISTROS VIDEOS
-- ============================================
INSERT INTO videos (titulo, url, fecha_publicacion) VALUES
('Corte Degradado', 'https://youtu.be/degradado1', '2025-01-05'),
('Estilo Clásico', 'https://youtu.be/clasico1', '2025-01-07'),
('Afeitado Barba', 'https://youtu.be/barba1', '2025-01-10'),
('Diseño de Corte', 'https://youtu.be/diseno1', '2025-01-12'),
('Técnicas Modernas', 'https://youtu.be/moderno1', '2025-01-15'),
('Tutorial Peinados', 'https://youtu.be/peinado1', '2025-01-18'),
('Uso de productos', 'https://youtu.be/productos1', '2025-01-20'),
('Experiencia Cliente', 'https://youtu.be/cliente1', '2025-01-22');

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
