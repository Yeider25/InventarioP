-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2025 a las 18:49:30
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambiente`
--

CREATE TABLE `ambiente` (
  `id_ambiente` int(11) NOT NULL,
  `nombre_ambiente` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `id_area` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ambiente`
--

INSERT INTO `ambiente` (`id_ambiente`, `nombre_ambiente`, `descripcion`, `id_area`) VALUES
(1, 'COORDINACION FORMACION PROFESIONAL', 'ubicado en tercer piso', 1),
(2, 'FORMACION PROFESIONAL', 'ubicado en tercer piso', 1),
(3, 'CAFETERIA', 'ubicado en tercer piso', 1),
(4, 'ADMINISTRACION EDUCATIVA', 'ubicado en tercer piso', 1),
(5, 'LABORATORIO', 'ubicado en tercer piso', 1),
(6, 'PRODUCCION', 'ubicado en tercer piso', 1),
(7, 'ALMACEN', 'ubicado en tercer piso', 1),
(8, 'GESTION DOCUMENTAL', 'ubicado en tercer piso', 2),
(9, 'SERIGRAFIA', 'ubicado en tercer piso', 2),
(10, 'SUBDIRECCION', 'ubicado en tercer piso', 1),
(11, 'IMPRESION OFSET', 'ubicado en tercer piso', 2),
(12, 'HUB CREATIVO', 'ubicado en tercer piso', 2),
(13, 'INSTRUCTORES', 'ubicado en tercer piso', 2),
(14, 'COORDINACION ACADEMICA', 'ubicado en tercer piso', 1),
(15, 'SERVICIOS GENERALES', 'ubicado en tercer piso', 1),
(16, 'FORMACION COMPLEMENTARIA', 'ubicado en tercer piso', 2),
(17, 'ARTICULACION CON LA MEDIA', 'ubicado en tercer piso', 1),
(18, 'TALLER ENI', 'ubicado en tercer piso', 2),
(19, 'ASEO', 'ubicado en tercer piso', 1),
(20, 'ENCUADERNACION', 'ubicado en tercer piso', 2),
(21, 'ADMINISTRATIVA', 'ubicado en tercer piso', 1),
(22, 'BOCETACION PARA DISEÑO GRAFICO', 'ubicado en tercer piso', 2),
(23, 'FLEXOGRAFIA', 'ubicado en tercer piso', 2),
(24, 'PRENSA', 'ubicado en tercer piso', 2),
(25, 'IMPRESION 3D', 'ubicado en tercer piso', 2),
(26, 'BIENESTAR', 'ubicado en tercer piso', 1),
(27, 'BILINGUISMO', 'ubicado en tercer piso', 2),
(28, 'TRABAJADOR OFICIAL', 'ubicado en tercer piso', 1),
(29, 'AUDIOVISUALES', 'ubicado en tercer piso', 2),
(30, 'algo', 'owo', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id`, `nombre`) VALUES
(1, 'ADMINISTRATIVO'),
(2, 'FORMACION');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentadante`
--

CREATE TABLE `cuentadante` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `documento` int(11) NOT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentadante`
--

INSERT INTO `cuentadante` (`id`, `nombre`, `documento`, `tipo`) VALUES


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentadante_solicitud`
--

CREATE TABLE `cuentadante_solicitud` (
  `id` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_cuentadante` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentadante_solicitud`
--

INSERT INTO `cuentadante_solicitud` (`id`, `id_solicitud`, `id_cuentadante`) VALUES


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_solicitud_periodica`
--

CREATE TABLE `detalle_solicitud_periodica` (
  `id` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `cod_elemento` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `unidad_medida` varchar(50) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `observacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elemento`
--

CREATE TABLE `elemento` (
  `id_elemento` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `und_medida` varchar(255) NOT NULL,
  `ambiente` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `cantidad_solicitada` int(11) DEFAULT NULL,
  `cantidad_entregada` int(11) DEFAULT 0,
  `nombre` varchar(255) NOT NULL,
  `estado` varchar(11) NOT NULL,
  `observaciones` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `elemento`
--

INSERT INTO `elemento` (`id_elemento`, `codigo`, `descripcion`, `und_medida`, `ambiente`, `cantidad`, `cantidad_solicitada`, `cantidad_entregada`, `nombre`, `estado`, `observaciones`) VALUES
(12, 123, 'Lapiz de mina fina para dibujo ', 'Caja', 29, 50, 12, 51, 'Lapiz # 8', 'Activo', ''),
(16, 789, 'Table de diseño grafico con su respectivo lapiz de dibujo, cargador  funda protectora de pantalla', 'Unidad', 12, 99, 10, 0, 'Table para diseño grafico', 'Activo', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elementos_solicitud_periodica`
--

CREATE TABLE `elementos_solicitud_periodica` (
  `id` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_elemento` int(11) NOT NULL,
  `cantidad_solicitada` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `elementos_solicitud_periodica`
--

INSERT INTO `elementos_solicitud_periodica` (`id`, `id_solicitud`, `id_elemento`, `cantidad_solicitada`, `observaciones`) VALUES


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elemento_solicitud_anual`
--

CREATE TABLE `elemento_solicitud_anual` (
  `id` int(11) NOT NULL,
  `fecha_soli` date NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_elemento` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `elemento_solicitud_anual`
--

INSERT INTO `elemento_solicitud_anual` (`id`, `fecha_soli`, `id_solicitud`, `id_elemento`, `cantidad`) VALUES


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE `especialidad` (
  `id` int(11) NOT NULL,
  `nombre_especialidad` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id`, `nombre_especialidad`) VALUES
(1, 'INGENIERO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha`
--

CREATE TABLE `ficha` (
  `numero_ficha` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ficha`
--

INSERT INTO `ficha` (`numero_ficha`, `id_programa`) VALUES
(2670272, 2),
(2670271, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instructor`
--

CREATE TABLE `instructor` (
  `id` int(11) NOT NULL,
  `cedula` int(11) NOT NULL,
  `nombre_instructor` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` int(11) NOT NULL,
  `especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `instructor`
--

INSERT INTO `instructor` (`id`, `cedula`, `nombre_instructor`, `celular`, `correo`, `contrasena`, `rol`, `especialidad`) VALUES
(5, 12345678, 'Maycol', '1234567890', 'ADMINEW@gmail.com', '$2y$10$Ky7niPwkrhEzwOPwX6K8hOZwxgK66VsHFMz.Y6Q9SqSTpVP4zxriK', 1, 1),
(9, 123456789, 'KAROL GONZALES', '123456789', 'Admin01@gmail.com', '$2y$10$6.9QamRkqE9eY/XtESJcM.gPCvgPkc0iIWfsM5pZuvnko5z3T2tjC', 1, 1),
(11, 456787, 'JORGE GARCIA', '456789123', 'Almacen@gmail.com', '$2y$10$pzkYDDY0CIJN6dk4iUIRguDAb.rnIcjIN.ULl02aOGGsXxMjiY2J.', 3, 1),
(12, 1000620538, 'NICOLAS RIOS', '1235456', 'Personal@gmail.com', '$2y$10$zTIaJW56Eb/XZWa1r72NneVNlex1IqGe1j1ODQ/evp2KdxYK4k3WS', 4, 1),
(16, 105150515, 'ESTIBEN VELA', '78541815', 'Prueba123@gmail.com', '$2y$10$TF9eA9Kcr2JeMtHlNWOlOuK.LIjoNGIR9xzf4iPN9zyj/NBWTSrv2', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento`
--

CREATE TABLE `mantenimiento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mantenimiento`
--

INSERT INTO `mantenimiento` (`id`, `nombre`) VALUES
(1, 'PREVENTIVO'),
(2, 'CORRECTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maquina`
--

CREATE TABLE `maquina` (
  `id` int(11) NOT NULL,
  `serial` int(11) NOT NULL,
  `adquisicion` date NOT NULL,
  `nombre_maquina` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `modelo` varchar(255) NOT NULL,
  `placa` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `id_ambiente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maquina`
--

INSERT INTO `maquina` (`id`, `serial`, `adquisicion`, `nombre_maquina`, `marca`, `modelo`, `placa`, `cantidad`, `id_ambiente`) VALUES


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa`
--

CREATE TABLE `programa` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(255) NOT NULL,
  `id_instructor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programa`
--

INSERT INTO `programa` (`id_programa`, `nombre_programa`, `id_instructor`) VALUES
(2, 'Análisis de Software', 12),
(3, 'Tecnico en desarrollo', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'ADMINISTRADOR'),
(2, 'COORDINADOR'),
(3, 'ALMACEN'),
(4, 'PERSONAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_anual`
--

CREATE TABLE `solicitud_anual` (
  `id_anual` int(11) NOT NULL,
  `fecha_soli` date NOT NULL,
  `nombre_solici` varchar(255) NOT NULL,
  `documento` int(11) NOT NULL,
  `ficha_soli` int(11) NOT NULL,
  `programa_soli` int(11) NOT NULL,
  `cantidad_soli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_anual`
--

INSERT INTO `solicitud_anual` (`id_anual`, `fecha_soli`, `nombre_solici`, `documento`, `ficha_soli`, `programa_soli`, `cantidad_soli`) VALUES


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_mantenimiento`
--

CREATE TABLE `solicitud_mantenimiento` (
  `id` int(11) NOT NULL,
  `solicitud` int(11) NOT NULL,
  `fecha_soli` date NOT NULL,
  `necesidad` varchar(255) NOT NULL,
  `maquina` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `suministro` varchar(255) NOT NULL,
  `id_instructor` int(11) NOT NULL,
  `id_ambiente` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_mantenimiento`
--

INSERT INTO `solicitud_mantenimiento` (`id`, `solicitud`, `fecha_soli`, `necesidad`, `maquina`, `tipo`, `suministro`, `id_instructor`, `id_ambiente`, `id_rol`, `observaciones`) VALUES


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_periodica`
--

CREATE TABLE `solicitud_periodica` (
  `id` int(11) NOT NULL,
  `cod_peri` varchar(255) NOT NULL,
  `fecha_soli` date NOT NULL,
  `area` int(11) NOT NULL,
  `cargo` varchar(255) NOT NULL,
  `cod_regi` int(11) NOT NULL,
  `nom_regi` varchar(255) NOT NULL,
  `cod_costo` int(11) NOT NULL,
  `nom_costo` varchar(255) NOT NULL,
  `nom_jefe` varchar(255) NOT NULL,
  `tipo_cuentadante` int(11) NOT NULL,
  `dest_bien` varchar(255) NOT NULL,
  `num_fich` int(11) NOT NULL,
  `firma` longblob DEFAULT NULL,
  `nombre_solici` varchar(255) NOT NULL,
  `documento_s` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_periodica`
--

INSERT INTO `solicitud_periodica` (`id`, `cod_peri`, `fecha_soli`, `area`, `cargo`, `cod_regi`, `nom_regi`, `cod_costo`, `nom_costo`, `nom_jefe`, `tipo_cuentadante`, `dest_bien`, `num_fich`, `firma`, `nombre_solici`, `documento_s`) VALUES

INSERT INTO `solicitud_periodica` (`id`, `cod_peri`, `fecha_soli`, `area`, `cargo`, `cod_regi`, `nom_regi`, `cod_costo`, `nom_costo`, `nom_jefe`, `tipo_cuentadante`, `dest_bien`, `num_fich`, `firma`, `nombre_solici`, `documento_s`) VALUES


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cuentadante`
--

CREATE TABLE `tipo_cuentadante` (
  `id_cuentadante` int(11) NOT NULL,
  `nombre_cuent` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_cuentadante`
--

INSERT INTO `tipo_cuentadante` (`id_cuentadante`, `nombre_cuent`) VALUES
(1, 'UNIPERSONAL'),
(2, 'MULTIPLE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_mantenimiento`
--

CREATE TABLE `tipo_mantenimiento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_mantenimiento`
--

INSERT INTO `tipo_mantenimiento` (`id`, `nombre`) VALUES
(1, 'MANTENIMIENTO DE INFRAESTRCTURA'),
(2, 'ADECUACIÓN DE INFRAESTRCTURA'),
(3, 'EVENTO'),
(4, 'SERVICIO'),
(5, 'OTRO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transaccional_reporte`
--

CREATE TABLE `transaccional_reporte` (
  `id_transaccional` int(11) NOT NULL,
  `total_solicitud` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ambiente`
--
ALTER TABLE `ambiente`
  ADD PRIMARY KEY (`id_ambiente`),
  ADD KEY `id_area` (`id_area`);

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentadante`
--
ALTER TABLE `cuentadante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo` (`tipo`);

--
-- Indices de la tabla `cuentadante_solicitud`
--
ALTER TABLE `cuentadante_solicitud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_solicitud` (`id_solicitud`),
  ADD KEY `id_cuentadante` (`id_cuentadante`);

--
-- Indices de la tabla `detalle_solicitud_periodica`
--
ALTER TABLE `detalle_solicitud_periodica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_solicitud` (`id_solicitud`);

--
-- Indices de la tabla `elemento`
--
ALTER TABLE `elemento`
  ADD PRIMARY KEY (`id_elemento`),
  ADD KEY `ambiente` (`ambiente`);

--
-- Indices de la tabla `elementos_solicitud_periodica`
--
ALTER TABLE `elementos_solicitud_periodica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_solicitud` (`id_solicitud`),
  ADD KEY `id_elemento` (`id_elemento`);

--
-- Indices de la tabla `elemento_solicitud_anual`
--
ALTER TABLE `elemento_solicitud_anual`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_solicitud_elemento` (`id_solicitud`,`id_elemento`),
  ADD UNIQUE KEY `id_solicitud_2` (`id_solicitud`,`id_elemento`),
  ADD KEY `id_solicitud` (`id_solicitud`),
  ADD KEY `id_elemento` (`id_elemento`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ficha`
--
ALTER TABLE `ficha`
  ADD PRIMARY KEY (`numero_ficha`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indices de la tabla `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol` (`rol`),
  ADD KEY `especialidad` (`especialidad`);

--
-- Indices de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `maquina`
--
ALTER TABLE `maquina`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ambiente` (`id_ambiente`);

--
-- Indices de la tabla `programa`
--
ALTER TABLE `programa`
  ADD PRIMARY KEY (`id_programa`),
  ADD KEY `id_instructor` (`id_instructor`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `solicitud_anual`
--
ALTER TABLE `solicitud_anual`
  ADD PRIMARY KEY (`id_anual`),
  ADD KEY `ficha_soli` (`ficha_soli`),
  ADD KEY `programa_soli` (`programa_soli`);

--
-- Indices de la tabla `solicitud_mantenimiento`
--
ALTER TABLE `solicitud_mantenimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud` (`solicitud`),
  ADD KEY `maquina` (`maquina`),
  ADD KEY `id_instructor` (`id_instructor`),
  ADD KEY `id_ambiente` (`id_ambiente`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `solicitud_periodica`
--
ALTER TABLE `solicitud_periodica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area` (`area`),
  ADD KEY `tipo_cuentadante` (`tipo_cuentadante`);

--
-- Indices de la tabla `tipo_cuentadante`
--
ALTER TABLE `tipo_cuentadante`
  ADD PRIMARY KEY (`id_cuentadante`);

--
-- Indices de la tabla `tipo_mantenimiento`
--
ALTER TABLE `tipo_mantenimiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transaccional_reporte`
--
ALTER TABLE `transaccional_reporte`
  ADD PRIMARY KEY (`id_transaccional`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ambiente`
--
ALTER TABLE `ambiente`
  MODIFY `id_ambiente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cuentadante`
--
ALTER TABLE `cuentadante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de la tabla `cuentadante_solicitud`
--
ALTER TABLE `cuentadante_solicitud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT de la tabla `detalle_solicitud_periodica`
--
ALTER TABLE `detalle_solicitud_periodica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `elemento`
--
ALTER TABLE `elemento`
  MODIFY `id_elemento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `elementos_solicitud_periodica`
--
ALTER TABLE `elementos_solicitud_periodica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT de la tabla `elemento_solicitud_anual`
--
ALTER TABLE `elemento_solicitud_anual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `instructor`
--
ALTER TABLE `instructor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `maquina`
--
ALTER TABLE `maquina`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `programa`
--
ALTER TABLE `programa`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `solicitud_anual`
--
ALTER TABLE `solicitud_anual`
  MODIFY `id_anual` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT de la tabla `solicitud_mantenimiento`
--
ALTER TABLE `solicitud_mantenimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT de la tabla `solicitud_periodica`
--
ALTER TABLE `solicitud_periodica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT de la tabla `tipo_cuentadante`
--
ALTER TABLE `tipo_cuentadante`
  MODIFY `id_cuentadante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_mantenimiento`
--
ALTER TABLE `tipo_mantenimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `transaccional_reporte`
--
ALTER TABLE `transaccional_reporte`
  MODIFY `id_transaccional` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ambiente`
--
ALTER TABLE `ambiente`
  ADD CONSTRAINT `ambiente_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `area` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cuentadante`
--
ALTER TABLE `cuentadante`
  ADD CONSTRAINT `cuentadante_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `tipo_cuentadante` (`id_cuentadante`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cuentadante_solicitud`
--
ALTER TABLE `cuentadante_solicitud`
  ADD CONSTRAINT `cuentadante_solicitud_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud_periodica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cuentadante_solicitud_ibfk_2` FOREIGN KEY (`id_cuentadante`) REFERENCES `cuentadante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_solicitud_periodica`
--
ALTER TABLE `detalle_solicitud_periodica`
  ADD CONSTRAINT `detalle_solicitud_periodica_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud_periodica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `elemento`
--
ALTER TABLE `elemento`
  ADD CONSTRAINT `elemento_ibfk_1` FOREIGN KEY (`ambiente`) REFERENCES `ambiente` (`id_ambiente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `elementos_solicitud_periodica`
--
ALTER TABLE `elementos_solicitud_periodica`
  ADD CONSTRAINT `elementos_solicitud_periodica_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud_periodica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `elementos_solicitud_periodica_ibfk_2` FOREIGN KEY (`id_elemento`) REFERENCES `elemento` (`id_elemento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `elemento_solicitud_anual`
--
ALTER TABLE `elemento_solicitud_anual`
  ADD CONSTRAINT `elemento_solicitud_anual_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud_anual` (`id_anual`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `elemento_solicitud_anual_ibfk_2` FOREIGN KEY (`id_elemento`) REFERENCES `elemento` (`id_elemento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ficha`
--
ALTER TABLE `ficha`
  ADD CONSTRAINT `ficha_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programa` (`id_programa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `instructor`
--
ALTER TABLE `instructor`
  ADD CONSTRAINT `instructor_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `instructor_ibfk_2` FOREIGN KEY (`especialidad`) REFERENCES `especialidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `maquina`
--
ALTER TABLE `maquina`
  ADD CONSTRAINT `maquina_ibfk_1` FOREIGN KEY (`id_ambiente`) REFERENCES `ambiente` (`id_ambiente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `programa`
--
ALTER TABLE `programa`
  ADD CONSTRAINT `programa_ibfk_1` FOREIGN KEY (`id_instructor`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud_anual`
--
ALTER TABLE `solicitud_anual`
  ADD CONSTRAINT `solicitud_anual_ibfk_1` FOREIGN KEY (`ficha_soli`) REFERENCES `ficha` (`numero_ficha`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitud_anual_ibfk_2` FOREIGN KEY (`programa_soli`) REFERENCES `programa` (`id_programa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud_mantenimiento`
--
ALTER TABLE `solicitud_mantenimiento`
  ADD CONSTRAINT `solicitud_mantenimiento_ibfk_1` FOREIGN KEY (`solicitud`) REFERENCES `tipo_mantenimiento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitud_mantenimiento_ibfk_2` FOREIGN KEY (`maquina`) REFERENCES `maquina` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitud_mantenimiento_ibfk_3` FOREIGN KEY (`id_instructor`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitud_mantenimiento_ibfk_4` FOREIGN KEY (`id_ambiente`) REFERENCES `ambiente` (`id_ambiente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitud_mantenimiento_ibfk_5` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud_periodica`
--
ALTER TABLE `solicitud_periodica`
  ADD CONSTRAINT `solicitud_periodica_ibfk_1` FOREIGN KEY (`area`) REFERENCES `ambiente` (`id_ambiente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitud_periodica_ibfk_2` FOREIGN KEY (`tipo_cuentadante`) REFERENCES `tipo_cuentadante` (`id_cuentadante`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
