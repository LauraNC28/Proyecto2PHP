-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-05-2025 a las 17:55:03
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(255) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Zapatillas Deportivas'),
(2, 'Zapatos de Vestir'),
(3, 'Sandalias'),
(4, 'Botas y Botines'),
(5, 'Calzado Casual'),
(6, 'Calzado Infantil'),
(7, 'Cuñas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas_pedidos`
--

CREATE TABLE `lineas_pedidos` (
  `id` int(255) NOT NULL,
  `pedido_id` int(255) NOT NULL,
  `producto_id` int(255) NOT NULL,
  `unidades` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `lineas_pedidos`
--

INSERT INTO `lineas_pedidos` (`id`, `pedido_id`, `producto_id`, `unidades`) VALUES
(1, 101, 1001, 1),
(2, 101, 1005, 1),
(3, 102, 1002, 1),
(4, 103, 1003, 2),
(5, 104, 1004, 1),
(6, 105, 1006, 1),
(7, 106, 1001, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(255) NOT NULL,
  `usuario_id` int(255) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `coste` float(200,2) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `provincia`, `localidad`, `direccion`, `coste`, `estado`, `fecha`, `hora`) VALUES
(101, 1, 'Madrid', 'Madrid', 'Calle Gran Vía 10, 3ºA', 125.99, 'completado', '2023-05-15', '10:30:00'),
(102, 2, 'Barcelona', 'Barcelona', 'Avenida Diagonal 200, Bajo', 75.00, 'pendiente', '2023-05-15', '11:00:00'),
(103, 1, 'Valencia', 'Paterna', 'Urbanización Monteclaro, Calle 5, nº 12', 45.50, 'enviado', '2023-05-16', '09:15:00'),
(104, 3, 'Sevilla', 'Tomares', 'Plaza del Altozano 5, 2ºB', 200.00, 'completado', '2023-05-16', '14:45:00'),
(105, 14, 'GRX', 'AT', 'camino albolote', 120.50, 'confirmed', '2025-05-27', '12:05:51'),
(106, 15, 'Bcn', 'At', 'bmrtnrn', 99.99, 'confirmed', '2025-05-27', '13:13:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(255) NOT NULL,
  `categoria_id` int(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` float(100,2) NOT NULL,
  `stock` int(255) NOT NULL,
  `oferta` varchar(2) NOT NULL,
  `fecha` date NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `stock`, `oferta`, `fecha`, `imagen`) VALUES
(1001, 4, 'Bota Chelsea de Cuero', 'Elegantes botas Chelsea de cuero genuino, perfectas para cualquier ocasión.', 99.99, 49, '0', '2023-01-20', 'botas1.jpg'),
(1002, 3, 'Cuñas de Esparto Veraniegas', 'Cómodas cuñas con suela de esparto, ideales para los días de verano.', 59.95, 75, '1', '2023-03-10', 'cuñas1.jpg'),
(1003, 3, 'Cuñas de Plataforma Casuales', 'Cuñas de plataforma con diseño moderno, perfectas para un look casual.', 65.00, 60, '0', '2023-03-15', 'cuñas2.jpg'),
(1004, 3, 'Sandalias Romanas Planas', 'Sandalias de estilo romano con tiras ajustables, muy cómodas y frescas.', 39.99, 100, '1', '2023-04-01', 'sandalias1.jpg'),
(1005, 3, 'Sandalias Elegantes', 'Sandalias con diseño sofisticado, ideales para eventos.', 79.90, 40, '0', '2023-04-05', 'sandalias2.jpg'),
(1006, 2, 'Zapatos de Vestir Oxford', 'Clásicos zapatos Oxford de piel para un look formal y profesional.', 120.50, 29, '0', '2023-02-28', 'zapatos1.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `email_verificado` tinyint(1) DEFAULT 0,
  `token_verificacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password`, `rol`, `imagen`, `email_verificado`, `token_verificacion`) VALUES
(1, 'Admin', 'Admin', 'admin@admin.com', '$2y$10$68MLNinGy91Aebprt30UJ.RTP0qUr.MnS7Dr3q9kRsulqAQwLQA86', 'admin', NULL, 0, NULL),
(2, 'Juan', 'Pérez', 'juan.perez@email.com', 'clave123', 'cliente', NULL, 0, NULL),
(3, 'María', 'González', 'maria.gonzalez@email.com', 'pass456', 'cliente', NULL, 0, NULL),
(4, 'Carlos', 'López', 'carlos.lopez@email.com', 'abcde123', 'cliente', NULL, 0, NULL),
(8, 'Juan', 'Pérez', 'juanx.perez@email.com', 'clave123', 'cliente', NULL, 0, NULL),
(9, 'María', 'Gómez', 'maria.gomez@email.com', 'password456', 'cliente', NULL, 0, NULL),
(10, 'Carlos', 'Ramírez', 'carlos.ramirez@email.com', 'securepass', 'cliente', NULL, 0, NULL),
(12, 'Laura', 'Nievas', 'laura@gamil.com', '$2y$04$7VScgiNAbFzaO1WbTTwMJecvhrZftyvz3IZANfd3cVF3/Cvb92IYS', 'user', NULL, 0, NULL),
(13, 'Noelia', 'Nievas', 'noelia@gmail.com', '$2y$04$Q/7pqwx8z9IRv9.uMrzP1utTr6rEvJPYbwlQg/0Bin29io6oBPX8q', 'admin', NULL, 0, NULL),
(14, 'Luz', 'Coca Galvez', 'luzcg@gmail.com', '$2y$04$nbKveDtk88qDfwsIoM/bH.kj.vBxGkhkYyR.bzZrq7M6qEVk3A8Ka', 'user', NULL, 0, NULL),
(15, 'Sergio', 'GR', 'sergiogarcia2002557@gmail.com', '$2y$04$oqxDOgl4.EIgLNqV0jV5JOl0ON6BsA88ZWpY21eFqZTAWAkJGM9D2', 'user', NULL, 0, NULL),
(16, 'Jorge', 'NL', 'jorgenievas32@gmail.com', '$2y$04$JhSlPvgh15XCwgtovwC4JuJ4Q2WNOJWd1NDtlxGAbWJb1EHfSklzi', '', NULL, 0, NULL),
(17, 'Lau', 'Nc', 'lau@gmail.com', '$2y$04$Dv4IMMQkhas5rV0kex/yNOrZvZvKK70PPJSgsU3WTJzW8YqfN9q.q', 'admin', NULL, 0, NULL),
(18, 'Daniel', 'RC', 'dani@gmail.com', '$2y$04$vHarB.hAlGEM5SpyCovRouCDwQZznSh66uV82q1vABkA..s0EUdNW', 'user', '', 0, 'd8ed4330105c1d2fcba72467c6d92050'),
(21, 'David', 'RC', 'david9@gmail.com', '$2y$04$H6Le7DS1LMop/ufTYOUATu8gvRk83AhsBs7T77i3FMq7Y4AKlOTiy', 'user', '', 0, 'fc0462a202d6d19c7980b5f544bfd6cf'),
(22, 'Luz', 'Coca Galvez', 'e.mariluzcoca@go.ugr.es', '$2y$04$/8F2I00EEpd.B8PiINifgO1ku6UU9rtzCQ74KKcIXTxDWv224siR.', 'user', '', 0, 'd9b8aad7f724c3f5b77172b1a6844fad'),
(25, 'Laura', 'Nievas', 'nievaslaura82@gmail.com', '$2y$04$nx.buELHpX1tzCNfw12.2e9Jqogtjbc1MSRShiBCoT0gunmcJKOha', 'user', '', 1, '3b6987d33e8fc2f7f517052ab44381fd');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lineas_pedidos`
--
ALTER TABLE `lineas_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_linea_pedido` (`pedido_id`),
  ADD KEY `fk_linea_producto` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedido_usuario` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto_categoria` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `lineas_pedidos`
--
ALTER TABLE `lineas_pedidos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `lineas_pedidos`
--
ALTER TABLE `lineas_pedidos`
  ADD CONSTRAINT `fk_linea_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `fk_linea_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
