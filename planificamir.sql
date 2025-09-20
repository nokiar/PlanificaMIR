-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2025 a las 15:43:47
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
-- Base de datos: `planificamir`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subjects`
--

INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'Digestivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `importance` int(3) DEFAULT 16,
  `rentability` int(3) DEFAULT 16,
  `reading` tinyint(1) NOT NULL DEFAULT 0,
  `esquema` tinyint(1) NOT NULL DEFAULT 0,
  `annotations` text DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `topics`
--

INSERT INTO `topics` (`id`, `subject_id`, `name`, `importance`, `rentability`, `reading`, `esquema`, `annotations`, `order_index`) VALUES
(1, 1, 'Esófago. Generalidades', 0, 0, 0, 0, NULL, 1),
(2, 1, 'Trastornos motores del esófago', 8, 16, 0, 0, NULL, 2),
(3, 1, 'ERGE y esofagitis', 22, 21, 0, 0, NULL, 3),
(4, 1, 'Tumores del esófago', 11, 25, 0, 0, NULL, 4),
(5, 1, 'Otras patologías esofágicas', 5, 11, 0, 0, NULL, 5),
(6, 1, 'Estómago. Generalidades', 0, 0, 0, 0, NULL, 6),
(7, 1, 'Helicobacter pylori', 10, 14, 0, 0, NULL, 7),
(8, 1, 'Dispepsia funcional. Gastritis y gastropatías', 12, 13, 0, 0, NULL, 8),
(9, 1, 'Úlcera gastroduodenal', 22, 16, 0, 0, NULL, 9),
(10, 1, 'Tumores del estómago', 23, 34, 0, 0, NULL, 10),
(11, 1, 'Intestino delgado. Generalidades', 0, 0, 0, 0, NULL, 11),
(12, 1, 'Síndromes de malabsorción', 17, 13, 0, 0, NULL, 12),
(13, 1, 'Tumores del intestino delgado', 2, 6, 0, 0, NULL, 13),
(14, 1, 'Intestino grueso. Generalidades', 2, 8, 0, 0, NULL, 14),
(15, 1, 'Enfermedad inflamatoria intestinal', 44, 25, 0, 0, NULL, 15),
(16, 1, 'Tumores del intestino grueso', 52, 39, 0, 0, NULL, 16),
(17, 1, 'Enfermedades vasculares del intestino', 13, 40, 0, 0, NULL, 17),
(18, 1, 'Alteraciones de la motilidad intestinal', 35, 42, 0, 0, NULL, 18),
(19, 1, 'Obstrucción intestinal', 18, 43, 0, 0, NULL, 19),
(20, 1, 'Patología apendicular', 15, 31, 0, 0, NULL, 20),
(21, 1, 'Hemorragia digestiva', 17, 36, 0, 0, NULL, 21),
(22, 1, 'Otras enfermedades gastrointestinales', 8, 31, 0, 0, NULL, 22),
(23, 1, 'Enfermedades recto-anales', 21, 48, 0, 0, NULL, 23),
(24, 1, 'Páncreas', 70, 31, 0, 0, NULL, 24),
(25, 1, 'Hígado y vías biliares: introducción', 0, 0, 0, 0, NULL, 25),
(26, 1, 'Ictericias', 4, 16, 0, 0, NULL, 26),
(27, 1, 'Cirrosis y hepatopatía alcohólica', 35, 18, 0, 0, NULL, 27),
(28, 1, 'Hepatitis víricas, tóxicas y autoinmunes', 29, 16, 0, 0, NULL, 28),
(29, 1, 'Hepatopatías colestásicas y metabólicas', 15, 13, 0, 0, NULL, 29),
(30, 1, 'Abscesos, quistes y tumores hepáticos. Trasplante', 48, 41, 0, 0, NULL, 30),
(31, 1, 'Enfermedades de vesícula y vía biliar', 51, 46, 0, 0, NULL, 31),
(32, 1, 'Enfermedades del peritoneo', 2, 4, 0, 0, NULL, 32),
(33, 1, 'Patología de la pared abdominal', 28, 70, 0, 0, NULL, 33),
(34, 1, 'Traumatismos abdominales', 5, 23, 0, 0, NULL, 34),
(35, 1, 'Manejo de las heridas', 11, 43, 0, 0, NULL, 35),
(36, 1, 'Repaso', 0, 0, 0, 0, NULL, 36),
(37, 1, 'Índice temático', 0, 0, 0, 0, NULL, 37);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `topic_rounds`
--

CREATE TABLE `topic_rounds` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `round_number` int(11) NOT NULL,
  `completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `topic_rounds`
--

-- --------------------------------------------------------

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indices de la tabla `topic_rounds`
--
ALTER TABLE `topic_rounds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `topic_id` (`topic_id`,`round_number`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `topic_rounds`
--
ALTER TABLE `topic_rounds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `topic_rounds`
--
ALTER TABLE `topic_rounds`
  ADD CONSTRAINT `topic_rounds_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
