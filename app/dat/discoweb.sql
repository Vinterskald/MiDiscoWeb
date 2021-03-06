-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-02-2020 a las 20:37:37
-- Versión del servidor: 10.1.35-MariaDB
-- Versión de PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `discoweb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` varchar(10) NOT NULL,
  `clave` varchar(70) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `plan` int(1) NOT NULL,
  `estado` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `clave`, `nombre`, `email`, `plan`, `estado`) VALUES
('admin', '$2y$10$WP/DAqb6tCgVY6zHiLDrpOCM8EvNdnKdcZDmbcKfF0zaVwbK2qO2q', 'Adminstrador', 'admin@system.com', 3, 'A'),
('prueba1', '$2y$10$lhwJX4zJiAmsRiy/NrRom.DY6vJWbJ3zMxMOLW16ozg88Sk3uMhzG', 'Pruebio', 'prueba@noexiste.meh', 1, 'A'),
('user01', '$2y$10$azCuINaGeh4g6.kKoO.qCesQxGeRBdy.XPiuoNy/LHD2bZ5K7gcni', 'Fernando Peréz', 'user01@gmailio.com', 0, 'A'),
('user02', '$2y$10$dNRm6K1LDc/AmX.n6/tHF.vY3SiNNMQ0pAcBzgL5Cw.x0EXdFu2Ce', 'Carmen García', 'user02@gmailio.com', 1, 'B'),
('yes33', '$2y$10$GbE2zGhEJc92uDnUdP9Lh.47n1kGdkfiekOM8NT7ZT5O2Kb6B.SW.', 'Jesica Rico', 'yes33@gmailio.com', 2, 'B');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
