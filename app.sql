-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2023 a las 00:23:07
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `app`
--
CREATE DATABASE IF NOT EXISTS `app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `app`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl-empleados`
--

CREATE TABLE IF NOT EXISTS `tbl-empleados` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Primernombre` varchar(255) NOT NULL,
  `Segundonombre` varchar(255) DEFAULT NULL,
  `Primerapellido` varchar(255) NOT NULL,
  `Segundoapellido` varchar(255) NOT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `CV` varchar(255) DEFAULT NULL,
  `Idpuesto` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Idpuesto` (`Idpuesto`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `tbl-empleados`:
--   `Idpuesto`
--       `tbl-puestos` -> `ID`
--

--
-- Volcado de datos para la tabla `tbl-empleados`
--

INSERT INTO `tbl-empleados` (`ID`, `Primernombre`, `Segundonombre`, `Primerapellido`, `Segundoapellido`, `Foto`, `CV`, `Idpuesto`, `Fecha`) VALUES(24, 'Charles', '', 'W.', 'Bachman', '1684816068_Charles_W._Bachman.jpg', '1684816068_Curriculum Vitae.pdf', 9, '2020-04-20');
INSERT INTO `tbl-empleados` (`ID`, `Primernombre`, `Segundonombre`, `Primerapellido`, `Segundoapellido`, `Foto`, `CV`, `Idpuesto`, `Fecha`) VALUES(26, 'Eduard', 'Frank', 'Codd', 'Smith', '1684820800_E.F.Codd.jpg', '1684820800_Curriculum Vitae.pdf', 7, '2017-10-01');
INSERT INTO `tbl-empleados` (`ID`, `Primernombre`, `Segundonombre`, `Primerapellido`, `Segundoapellido`, `Foto`, `CV`, `Idpuesto`, `Fecha`) VALUES(27, 'Hilda', 'Karina', 'Montez', 'Cespedez', '1684820901_persona.jpg', '1684820901_Curriculum Vitae.pdf', 9, '2022-02-04');
INSERT INTO `tbl-empleados` (`ID`, `Primernombre`, `Segundonombre`, `Primerapellido`, `Segundoapellido`, `Foto`, `CV`, `Idpuesto`, `Fecha`) VALUES(28, 'Sofia', 'Nita', 'Rodriguez', 'Martinez', '1684884592_persona2.jpg', '1684884592_Curriculum Vitae.pdf', 18, '2020-04-23');
INSERT INTO `tbl-empleados` (`ID`, `Primernombre`, `Segundonombre`, `Primerapellido`, `Segundoapellido`, `Foto`, `CV`, `Idpuesto`, `Fecha`) VALUES(30, 'Player', 'Station', 'Sony', 'Nintendo', '1684890529_player1.png', '1684890529_ServiciosWeb.pdf', 24, '2019-03-23');
INSERT INTO `tbl-empleados` (`ID`, `Primernombre`, `Segundonombre`, `Primerapellido`, `Segundoapellido`, `Foto`, `CV`, `Idpuesto`, `Fecha`) VALUES(31, 'Hugo', 'Vicente', 'Griffin', 'Brown', '1684899197_persona5.jpg', '1684899197_ServiciosWeb.pdf', 24, '2021-02-07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl-puestos`
--

CREATE TABLE IF NOT EXISTS `tbl-puestos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombredelpuesto` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `tbl-puestos`:
--

--
-- Volcado de datos para la tabla `tbl-puestos`
--

INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(7, 'Programador Semi Sr.');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(8, 'Programador Sr.');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(9, 'Líder de Proyectos');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(12, 'Lider de proyectos');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(14, 'Desarrolador de base de datos');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(16, 'Tecnico de Base de Datos');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(17, 'Programado Web');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(18, 'Programador Fullstack');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(19, 'Programador Frontend');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(20, 'Programador Jr.');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(24, 'Diseñador Web');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl-usuarios`
--

CREATE TABLE IF NOT EXISTS `tbl-usuarios` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombreusuario` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Correo` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `tbl-usuarios`:
--

--
-- Volcado de datos para la tabla `tbl-usuarios`
--

INSERT INTO `tbl-usuarios` (`ID`, `Nombreusuario`, `Password`, `Correo`) VALUES(1, 'Administrador', '1234', 'admin@gmail.com');
INSERT INTO `tbl-usuarios` (`ID`, `Nombreusuario`, `Password`, `Correo`) VALUES(4, 'Kevin 11', '7896', 'Juanito@gmail.com');
INSERT INTO `tbl-usuarios` (`ID`, `Nombreusuario`, `Password`, `Correo`) VALUES(5, 'Hacker', 'Hackerman15', 'Hackandslash@hotmail.com');
INSERT INTO `tbl-usuarios` (`ID`, `Nombreusuario`, `Password`, `Correo`) VALUES(6, 'James007', 'Pass007', 'James007@gmail.com');
INSERT INTO `tbl-usuarios` (`ID`, `Nombreusuario`, `Password`, `Correo`) VALUES(8, 'George15', '123789', 'GeorgeWild@gmail.com');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl-empleados`
--
ALTER TABLE `tbl-empleados`
  ADD CONSTRAINT `tbl-empleados_ibfk_1` FOREIGN KEY (`Idpuesto`) REFERENCES `tbl-puestos` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
