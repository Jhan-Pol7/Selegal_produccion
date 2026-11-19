-- =====================================================
-- PROYECTO: CENTRO DE CONCILIACION EXTRAJUDICIAL SELEGAL
-- BASE DE DATOS: selegal_db
-- MOTOR: MySQL 8.0.16+
-- =====================================================

CREATE DATABASE IF NOT EXISTS selegal_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE selegal_db;

SELECT DATABASE() AS base_de_datos_actual;


-- =====================================================
-- 1. ASOCIACIONES
-- =====================================================

CREATE TABLE IF NOT EXISTS asociaciones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

SELECT * FROM asociaciones;


-- =====================================================
-- 2. CENTROS
-- =====================================================

CREATE TABLE IF NOT EXISTS centros (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    asociacion_id BIGINT UNSIGNED NOT NULL UNIQUE,
    nombre_comercial VARCHAR(150) NOT NULL,
    nombre_oficial VARCHAR(200) NOT NULL,
    resolucion_directoral VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    whatsapp VARCHAR(20),
    email VARCHAR(150),
    direccion TEXT,
    direccion_acreditada BOOLEAN NOT NULL DEFAULT FALSE,
    mostrar_direccion BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_centros_asociaciones
        FOREIGN KEY (asociacion_id)
        REFERENCES asociaciones(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_direccion_publica
        CHECK (
            mostrar_direccion = FALSE
            OR direccion_acreditada = TRUE
        )
) ENGINE=InnoDB;

SELECT * FROM centros;


-- =====================================================
-- 3. USUARIOS ADMINISTRADORES
-- =====================================================

CREATE TABLE IF NOT EXISTS usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('ADMINISTRADOR', 'PERSONAL')
        NOT NULL DEFAULT 'PERSONAL',
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

SELECT * FROM usuarios;


-- =====================================================
-- 4. MATERIAS
-- =====================================================

CREATE TABLE IF NOT EXISTS materias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
) ENGINE=InnoDB;

SELECT * FROM materias;


-- =====================================================
-- 5. EQUIPO PROFESIONAL
-- =====================================================

CREATE TABLE IF NOT EXISTS equipo (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    centro_id BIGINT UNSIGNED NOT NULL,
    nombre_completo VARCHAR(150) NOT NULL,
    cargo ENUM('ABOGADO', 'CONCILIADOR', 'DIRECTOR')
        NOT NULL,
    descripcion TEXT,
    fotografia VARCHAR(255),
    visible BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (centro_id)
        REFERENCES centros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

SELECT * FROM equipo;


-- =====================================================
-- 6. MATERIAS DEL EQUIPO
-- RELACION MUCHOS A MUCHOS
-- =====================================================

CREATE TABLE IF NOT EXISTS equipo_materias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    equipo_id BIGINT UNSIGNED NOT NULL,
    materia_id BIGINT UNSIGNED NOT NULL,

    UNIQUE KEY uk_equipo_materia (equipo_id, materia_id),

    FOREIGN KEY (equipo_id)
        REFERENCES equipo(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (materia_id)
        REFERENCES materias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

SELECT * FROM equipo_materias;


-- =====================================================
-- 7. SERVICIOS
-- =====================================================

CREATE TABLE IF NOT EXISTS servicios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    centro_id BIGINT UNSIGNED NOT NULL,
    materia_id BIGINT UNSIGNED NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    orden INT NOT NULL DEFAULT 0,

    UNIQUE KEY uk_centro_servicio (centro_id, nombre),

    FOREIGN KEY (centro_id)
        REFERENCES centros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (materia_id)
        REFERENCES materias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

SELECT * FROM servicios;


-- =====================================================
-- 8. TESTIMONIOS
-- =====================================================

CREATE TABLE IF NOT EXISTS testimonios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    centro_id BIGINT UNSIGNED NOT NULL,
    nombre_cliente VARCHAR(150) NOT NULL,
    contenido TEXT NOT NULL,
    visible BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (centro_id)
        REFERENCES centros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

SELECT * FROM testimonios;


-- =====================================================
-- 9. REDES SOCIALES
-- =====================================================

CREATE TABLE IF NOT EXISTS redes_sociales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    centro_id BIGINT UNSIGNED NOT NULL,
    plataforma ENUM('FACEBOOK', 'INSTAGRAM', 'TIKTOK')
        NOT NULL,
    url VARCHAR(255) NOT NULL,
    visible BOOLEAN NOT NULL DEFAULT TRUE,

    UNIQUE KEY uk_centro_red (centro_id, plataforma),

    FOREIGN KEY (centro_id)
        REFERENCES centros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

SELECT * FROM redes_sociales;


-- =====================================================
-- 10. SOLICITANTES
-- =====================================================

CREATE TABLE IF NOT EXISTS solicitantes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

SELECT * FROM solicitantes;


-- =====================================================
-- 11. SOLICITUDES DE CONCILIACION
-- =====================================================

CREATE TABLE IF NOT EXISTS solicitudes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    centro_id BIGINT UNSIGNED NOT NULL,
    solicitante_id BIGINT UNSIGNED NOT NULL,
    materia_id BIGINT UNSIGNED NOT NULL,
    descripcion TEXT NOT NULL,

    estado ENUM(
        'PENDIENTE',
        'EN_REVISION',
        'ATENDIDA',
        'RECHAZADA'
    ) NOT NULL DEFAULT 'PENDIENTE',

    acepta_tratamiento_datos BOOLEAN NOT NULL DEFAULT FALSE,
    fecha_consentimiento DATETIME,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (centro_id)
        REFERENCES centros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (solicitante_id)
        REFERENCES solicitantes(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (materia_id)
        REFERENCES materias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_solicitudes_estado (estado),
    INDEX idx_solicitudes_fecha (fecha_solicitud)
) ENGINE=InnoDB;

SELECT * FROM solicitudes;


-- =====================================================
-- 12. PARTES INVOLUCRADAS EN LA SOLICITUD
-- DATOS DE LA OTRA PARTE: PENDIENTES DE VALIDACION
-- =====================================================

CREATE TABLE IF NOT EXISTS partes_solicitud (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    solicitud_id BIGINT UNSIGNED NOT NULL,
    nombre_completo VARCHAR(150),
    dni VARCHAR(20),
    telefono VARCHAR(20),
    direccion TEXT,

    FOREIGN KEY (solicitud_id)
        REFERENCES solicitudes(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

SELECT * FROM partes_solicitud;


-- =====================================================
-- 13. HORARIOS DE ATENCION
-- CONFIGURACION SEMANAL DEL ADMINISTRADOR
-- dia_semana: 1=Lunes ... 7=Domingo
-- =====================================================

CREATE TABLE IF NOT EXISTS horarios_atencion (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    centro_id BIGINT UNSIGNED NOT NULL,
    dia_semana TINYINT UNSIGNED NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    duracion_minutos SMALLINT UNSIGNED NOT NULL DEFAULT 60,
    activo BOOLEAN NOT NULL DEFAULT TRUE,

    FOREIGN KEY (centro_id)
        REFERENCES centros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_dia_semana
        CHECK (dia_semana BETWEEN 1 AND 7),

    CONSTRAINT chk_horas
        CHECK (hora_inicio < hora_fin),

    CONSTRAINT chk_duracion
        CHECK (duracion_minutos > 0),

    UNIQUE KEY uk_horario (
        centro_id,
        dia_semana,
        hora_inicio,
        hora_fin
    )
) ENGINE=InnoDB;

SELECT * FROM horarios_atencion;


-- =====================================================
-- 14. CITAS
-- UNA SOLICITUD PUEDE TENER UNA CITA
-- EL HORARIO SE BLOQUEA AL RESERVARSE
-- =====================================================

CREATE TABLE IF NOT EXISTS citas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    solicitud_id BIGINT UNSIGNED NOT NULL UNIQUE,
    centro_id BIGINT UNSIGNED NOT NULL,
    horario_atencion_id BIGINT UNSIGNED NOT NULL,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,

    estado ENUM(
        'PENDIENTE',
        'CONFIRMADA',
        'REAGENDADA',
        'CANCELADA',
        'FINALIZADA'
    ) NOT NULL DEFAULT 'PENDIENTE',

    observacion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    -- Una reserva activa ocupa un horario.
    -- Las citas canceladas liberan el espacio.
    slot_activo VARCHAR(32)
        GENERATED ALWAYS AS (
            CASE
                WHEN estado = 'CANCELADA' THEN NULL
                ELSE CONCAT(fecha, ' ', hora_inicio)
            END
        ) STORED,

    UNIQUE KEY uk_cita_horario (centro_id, slot_activo),

    FOREIGN KEY (solicitud_id)
        REFERENCES solicitudes(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (centro_id)
        REFERENCES centros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (horario_atencion_id)
        REFERENCES horarios_atencion(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_hora_cita
        CHECK (hora_inicio < hora_fin),

    INDEX idx_citas_fecha (fecha),
    INDEX idx_citas_estado (estado)
) ENGINE=InnoDB;

SELECT * FROM citas;


-- =====================================================
-- 15. NOTIFICACIONES
-- AVISOS DE NUEVAS SOLICITUDES AL CENTRO
-- =====================================================

CREATE TABLE IF NOT EXISTS notificaciones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    solicitud_id BIGINT UNSIGNED NOT NULL,
    tipo ENUM(
        'NUEVA_SOLICITUD',
        'CITA_REGISTRADA',
        'CITA_CANCELADA',
        'CITA_REPROGRAMADA'
    ) NOT NULL,
    canal ENUM('EMAIL', 'SISTEMA') NOT NULL DEFAULT 'SISTEMA',
    destinatario VARCHAR(150),
    mensaje TEXT NOT NULL,
    estado ENUM(
        'PENDIENTE',
        'ENVIADA',
        'FALLIDA'
    ) NOT NULL DEFAULT 'PENDIENTE',
    fecha_envio DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (solicitud_id)
        REFERENCES solicitudes(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_notificaciones_estado (estado)
) ENGINE=InnoDB;

SELECT * FROM notificaciones;


-- =====================================================
-- 16. DATOS INICIALES DE SELEGAL
-- =====================================================

-- ASOCIACION MATRIZ

INSERT INTO asociaciones (nombre, descripcion)
VALUES (
    'Corporación de Abogados e Ingenieros por la Defensa de los Derechos Fundamentales',
    'Asociación matriz del Centro de Conciliación Extrajudicial SELEGAL.'
)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

SELECT * FROM asociaciones;


-- CENTRO SELEGAL

INSERT INTO centros (
    asociacion_id,
    nombre_comercial,
    nombre_oficial,
    resolucion_directoral,
    telefono,
    whatsapp,
    email,
    direccion,
    direccion_acreditada,
    mostrar_direccion
)
SELECT
    a.id,
    'SELEGAL',
    'Centro de Conciliación Extrajudicial SELEGAL',
    'R.D. N.° 364-2026-JUS/DGDPAJ/DCMA',
    '972408889',
    '972408889',
    'Selegal.Centrodeconciliacion@gmail.com',
    NULL,
    FALSE,
    FALSE
FROM asociaciones a
WHERE a.nombre =
    'Corporación de Abogados e Ingenieros por la Defensa de los Derechos Fundamentales'
AND NOT EXISTS (
    SELECT 1
    FROM centros c
    WHERE c.nombre_comercial = 'SELEGAL'
);

SELECT * FROM centros;


-- MATERIAS

INSERT IGNORE INTO materias (nombre, descripcion)
VALUES
(
    'Civil',
    'Servicios de conciliación en materia civil.'
),
(
    'Familiar',
    'Servicios de conciliación en materia familiar.'
);

SELECT * FROM materias;


-- SERVICIOS CIVILES Y FAMILIARES

INSERT IGNORE INTO servicios (
    centro_id,
    materia_id,
    nombre,
    descripcion,
    orden
)
SELECT
    c.id,
    m.id,
    s.nombre,
    s.descripcion,
    s.orden
FROM centros c
CROSS JOIN materias m
JOIN (
    SELECT 'Civil' AS materia, 'Indemnizaciones' AS nombre,
        'Conciliación sobre indemnizaciones.' AS descripcion, 1 AS orden
    UNION ALL
    SELECT 'Civil', 'Desalojos',
        'Conciliación sobre desalojos.', 2
    UNION ALL
    SELECT 'Civil', 'Otorgamiento de escritura pública',
        'Conciliación sobre otorgamiento de escritura pública.', 3
    UNION ALL
    SELECT 'Civil', 'División y partición',
        'Conciliación sobre división y partición.', 4
    UNION ALL
    SELECT 'Civil', 'Liquidación de sociedad de gananciales',
        'Conciliación sobre liquidación de sociedad de gananciales.', 5
    UNION ALL
    SELECT 'Familiar', 'Pensión de alimentos',
        'Conciliación sobre pensión de alimentos.', 1
    UNION ALL
    SELECT 'Familiar', 'Tenencia',
        'Conciliación sobre tenencia.', 2
    UNION ALL
    SELECT 'Familiar', 'Régimen de visitas',
        'Conciliación sobre régimen de visitas.', 3
    UNION ALL
    SELECT 'Familiar', 'Reducción de alimentos',
        'Conciliación sobre reducción de alimentos.', 4
) AS s
    ON s.materia = m.nombre
WHERE c.nombre_comercial = 'SELEGAL';

SELECT
    s.id,
    m.nombre AS materia,
    s.nombre AS servicio,
    s.descripcion,
    s.activo
FROM servicios s
INNER JOIN materias m ON s.materia_id = m.id
ORDER BY m.nombre, s.orden;


-- =====================================================
-- 17. CONSULTAS DE VERIFICACION GENERAL
-- =====================================================

-- Ver todas las tablas de la base de datos

SHOW TABLES;


-- Verificar asociacion y centro

SELECT
    c.id AS centro_id,
    c.nombre_comercial,
    c.nombre_oficial,
    a.nombre AS asociacion,
    c.resolucion_directoral,
    c.telefono,
    c.email
FROM centros c
INNER JOIN asociaciones a
    ON c.asociacion_id = a.id;


-- Ver las materias y sus servicios

SELECT
    m.nombre AS materia,
    s.nombre AS servicio
FROM servicios s
INNER JOIN materias m
    ON s.materia_id = m.id
ORDER BY m.nombre, s.nombre;


-- Ver los integrantes del equipo y sus materias

SELECT
    e.nombre_completo,
    e.cargo,
    m.nombre AS materia
FROM equipo e
LEFT JOIN equipo_materias em
    ON e.id = em.equipo_id
LEFT JOIN materias m
    ON em.materia_id = m.id;


-- Cantidad de solicitudes nuevas (RN-22)

SELECT
    COUNT(*) AS solicitudes_nuevas
FROM solicitudes
WHERE estado = 'PENDIENTE';


-- Consultar solicitudes recibidas

SELECT
    s.id,
    so.nombre_completo AS solicitante,
    so.telefono,
    m.nombre AS materia,
    s.estado,
    s.fecha_solicitud
FROM solicitudes s
INNER JOIN solicitantes so
    ON s.solicitante_id = so.id
INNER JOIN materias m
    ON s.materia_id = m.id
ORDER BY s.fecha_solicitud DESC;


-- Consultar proximas citas

SELECT
    c.id AS cita_id,
    so.nombre_completo AS solicitante,
    c.fecha,
    c.hora_inicio,
    c.hora_fin,
    c.estado
FROM citas c
INNER JOIN solicitudes s
    ON c.solicitud_id = s.id
INNER JOIN solicitantes so
    ON s.solicitante_id = so.id
WHERE c.fecha >= CURDATE()
AND c.estado IN ('PENDIENTE', 'CONFIRMADA', 'REAGENDADA')
ORDER BY c.fecha, c.hora_inicio;


-- Consultar testimonios visibles

SELECT *
FROM testimonios
WHERE visible = TRUE;


-- Consultar horarios activos

SELECT
    dia_semana,
    hora_inicio,
    hora_fin,
    duracion_minutos
FROM horarios_atencion
WHERE activo = TRUE
ORDER BY dia_semana, hora_inicio;


-- =====================================================
-- FIN DEL SCRIPT SELEGAL
-- =====================================================