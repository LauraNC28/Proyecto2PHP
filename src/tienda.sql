-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2025 at 10:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tienda`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int(255) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Comedores'),
(2, 'Dormitorios'),
(3, 'Estudio'),
(4, 'Exteriores'),
(5, 'Sala'),
(6, 'Sofas');

-- --------------------------------------------------------

--
-- Table structure for table `lineas_pedidos`
--

CREATE TABLE `lineas_pedidos` (
  `id` int(255) NOT NULL,
  `pedido_id` int(255) NOT NULL,
  `producto_id` int(255) NOT NULL,
  `unidades` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lineas_pedidos`
--

INSERT INTO `lineas_pedidos` (`id`, `pedido_id`, `producto_id`, `unidades`) VALUES
(6, 7, 12, 3),
(7, 7, 4, 1),
(8, 7, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(255) NOT NULL,
  `usuario_id` int(255) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `coste` float(200,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `provincia`, `localidad`, `direccion`, `coste`, `estado`, `fecha`, `hora`) VALUES
(7, 13, 'loja', 'loaj', 'san cecilop', 1500.00, 'confirmed', '2025-03-02', '21:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(255) NOT NULL,
  `categoria_id` int(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` float(100,2) NOT NULL,
  `stock` int(255) NOT NULL,
  `oferta` varchar(2) DEFAULT NULL,
  `fecha` date NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `email_verificado` TINYINT(1) DEFAULT 0,
  `token_verificacion` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `stock`, `oferta`, `fecha`, `imagen`) VALUES
(1, 1, 'Mesa de Comedor Moderna', 'Mesa de madera con diseño elegante para 6 personas.', 350.00, 10, '0', '2025-03-02', 'mesa_comedor.jpg'),
(2, 1, 'Silla de Comedor', 'Silla acolchonada con estructura de acero.', 75.00, 25, '1', '2025-03-02', 'silla_comedor.jpg'),
(3, 2, 'Cama King Size', 'Cama amplia con cabecera de madera y colchón ortopédico.', 600.00, 5, '0', '2025-03-02', 'cama_king.jpg'),
(4, 2, 'Velador de Madera', 'Mesa de noche con cajón y espacio de almacenamiento.', 120.00, 14, '1', '2025-03-02', 'velador.jpg'),
(5, 3, 'Escritorio Minimalista', 'Escritorio compacto ideal para oficina o estudio.', 250.00, 8, '0', '2025-03-02', 'escritorio.jpg'),
(6, 3, 'Silla Ergonómica', 'Silla de oficina ajustable con soporte lumbar.', 180.00, 11, '1', '2025-03-02', 'silla_estudio.jpg'),
(7, 4, 'Set de Jardín', 'Conjunto de mesa y sillas para exteriores en ratán sintético.', 450.00, 6, '0', '2025-03-02', 'set_jardin.jpg'),
(8, 4, 'Sombrilla para Terraza', 'Sombrilla grande con base resistente.', 200.00, 10, '1', '2025-03-02', 'sombrilla.jpg'),
(9, 5, 'Sofá Seccional', 'Sofá grande en L con cojines suaves.', 800.00, 4, '0', '2025-03-02', 'sofa_seccional.jpg'),
(10, 5, 'Mesa de Centro', 'Mesa de centro de vidrio templado y base de acero.', 180.00, 10, '1', '2025-03-02', 'mesa_centro.jpg'),
(11, 6, 'Sofá de Cuero', 'Sofá de cuero genuino para tres personas.', 950.00, 3, '0', '2025-03-02', 'sofa_cuero.jpg'),
(12, 6, 'Sillón Reclinable', 'Sillón individual con mecanismo reclinable.', 400.00, 4, '1', '2025-03-02', 'sillon_reclinable.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password`, `rol`, `imagen`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '$2y$10$mEpeMpRKfocKqPGacmpYlOloCqbFODfoQ/sf1.i2GADB/2T2mAJCW', 'admin', NULL),
(2, 'Juan', 'Pérez', 'juan.perez@email.com', 'clave123', 'cliente', NULL),
(3, 'María', 'González', 'maria.gonzalez@email.com', 'pass456', 'cliente', NULL),
(4, 'Carlos', 'López', 'carlos.lopez@email.com', 'abcde123', 'cliente', NULL),
(8, 'Juan', 'Pérez', 'juanx.perez@email.com', 'clave123', 'cliente', NULL),
(9, 'María', 'Gómez', 'maria.gomez@email.com', 'password456', 'cliente', NULL),
(10, 'Carlos', 'Ramírez', 'carlos.ramirez@email.com', 'securepass', 'cliente', NULL),
(12, 'Adrian', 'Guerra', 'adriancamilo@gamil.com', '$2y$04$7VScgiNAbFzaO1WbTTwMJecvhrZftyvz3IZANfd3cVF3/Cvb92IYS', 'user', NULL),
(13, 'adrian', 'guerra', 'cokugamer102@gmail.com', '$2y$04$Q/7pqwx8z9IRv9.uMrzP1utTr6rEvJPYbwlQg/0Bin29io6oBPX8q', 'user', NULL),
(17, 'luciano', 'guerra', 'luci@gmail.com', '$2y$04$EVR.ByKGbOM8T6I0BeLGfeMeXkAcjsUtY8mVSz48BSUhwWHsjHReW', 'user', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lineas_pedidos`
--
ALTER TABLE `lineas_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lineas_pedido` (`pedido_id`),
  ADD KEY `fk_lineas_producto` (`producto_id`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedidos_usuario` (`usuario_id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto_categoria` (`categoria_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lineas_pedidos`
--
ALTER TABLE `lineas_pedidos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lineas_pedidos`
--
ALTER TABLE `lineas_pedidos`
  ADD CONSTRAINT `fk_lineas_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `fk_lineas_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
