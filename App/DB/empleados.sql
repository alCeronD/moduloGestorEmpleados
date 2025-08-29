-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-08-2025 a las 18:25:32
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
-- Base de datos: `empleados`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `idArea` int(11) NOT NULL COMMENT 'Identificador primario del area',
  `nombre` varchar(255) NOT NULL COMMENT 'Nombre del área de la empresa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`idArea`, `nombre`) VALUES
(1, 'Ventas'),
(2, 'Producción'),
(3, 'Calidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `idEmpleado` int(11) NOT NULL COMMENT 'Identificador del empleado',
  `nombre` varchar(255) NOT NULL COMMENT 'Nombre completo del empleado, obligatorio',
  `email` varchar(255) NOT NULL COMMENT 'Email empleado, obligatorio',
  `sexo` char(1) NOT NULL COMMENT 'Campo de genero donde M(Masculino) y F(Femenino) obligatorio',
  `area_id` int(11) DEFAULT NULL COMMENT 'Area en donde pertenece el empleado',
  `boletin` int(11) NOT NULL COMMENT 'Id referenciado para recibir boletin donde 1 es recibir boletín, 0 para no recibir boletín',
  `descripcion` text NOT NULL COMMENT 'Campo de descripción del empleado, obligatorio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`idEmpleado`, `nombre`, `email`, `sexo`, `area_id`, `boletin`, `descripcion`) VALUES
(1, 'Carlos Ramírez', 'carlos.ramirez@example.com', 'M', 1, 1, 'Desarrollador con experiencia en aplicaciones web y móviles.'),
(2, 'Fernando Doe', 'doe.Fernando@example.com', 'M', 2, 1, 'Gerente con enfoque en estrategias de crecimiento organizacional.'),
(3, 'Juan Dario', 'juan.perez@xample.com', 'F', 3, 0, 'Auxiliar Administrativo, cumple buenas tareas.'),
(4, 'Ana Torres', 'ana.torres@example.com', 'F', 1, 0, 'Desarrolladora apasionada por la innovación tecnológica.'),
(5, 'Jhon doe', 'doe.3@gmail.com', 'M', 1, 1, 'Jhon doe trabaja en el area de ventas con 3 años de experiencia en el sector digital'),
(6, 'Maria Antonia Pilar Vellazco', 'maria.pilar@hotmail.es', 'F', 2, 1, 'Señora pilar, parte del equipo ejecutivo de produccción.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado_rol`
--

CREATE TABLE `empleado_rol` (
  `idEmpleadoRol` int(11) NOT NULL COMMENT 'Id primario AUTO_INCREMENT de la tabla muchos a muchos entre empleado y rol',
  `empleadoId` int(11) DEFAULT NULL COMMENT 'Identificador llave foraneo del empleado',
  `rol_id` int(11) DEFAULT NULL COMMENT 'Identificador llave foranea del rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado_rol`
--

INSERT INTO `empleado_rol` (`idEmpleadoRol`, `empleadoId`, `rol_id`) VALUES
(1, 1, 1),
(17, 4, 1),
(18, 2, 3),
(21, 3, 1),
(22, 3, 2),
(24, 5, 3),
(25, 5, 1),
(27, 6, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idRol` int(11) NOT NULL COMMENT 'Identificador primario del rol',
  `nombre` varchar(255) NOT NULL COMMENT 'Nombre del rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idRol`, `nombre`) VALUES
(1, 'Profesional de proyectos - Desarrollador'),
(2, 'Gerente estratégico'),
(3, 'Auxiliar administrativo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`idArea`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`idEmpleado`),
  ADD KEY `FK_area_id` (`area_id`);

--
-- Indices de la tabla `empleado_rol`
--
ALTER TABLE `empleado_rol`
  ADD PRIMARY KEY (`idEmpleadoRol`),
  ADD KEY `FK_empleadoID` (`empleadoId`),
  ADD KEY `FK_rolID` (`rol_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idRol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `idArea` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador primario del area', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `idEmpleado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del empleado', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `empleado_rol`
--
ALTER TABLE `empleado_rol`
  MODIFY `idEmpleadoRol` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id primario AUTO_INCREMENT de la tabla muchos a muchos entre empleado y rol', AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idRol` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador primario del rol', AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `FK_area_id` FOREIGN KEY (`area_id`) REFERENCES `area` (`idArea`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleado_rol`
--
ALTER TABLE `empleado_rol`
  ADD CONSTRAINT `FK_empleadoID` FOREIGN KEY (`empleadoId`) REFERENCES `empleados` (`idEmpleado`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_rolID` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`idRol`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
