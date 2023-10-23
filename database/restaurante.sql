
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dados: `restaurante`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela para a tabela `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `cidade` text NOT NULL,
  `mensagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 
--

INSERT INTO `config` (`id`, `nome`, `telefone`, `email`, `cidade`, `mensagem`) VALUES
(1, 'Restaurante RestoBar', '957847894', 'restobar@gmail.com', 'Lima - Perú', 'Gracias por la compra');

-- --------------------------------------------------------

--
-- Estrutura de tabela para a tabela `detalhe_pedidos`
--

CREATE TABLE `detalhe_pedidos` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--

-- --------------------------------------------------------

--
-- Estrutura de tabela para a tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `num_mesa` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `observacao` text DEFAULT NULL,
  `estado` enum('PENDENTE','FINALIZADO') NOT NULL DEFAULT 'PENDENTE',
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;;


-- --------------------------------------------------------

--
-- Estrutura de tabEla para la tabEla `pratos`
--

CREATE TABLE `pratos` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(100) DEFAULT NULL,
  `data` timestamp NULL DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;;


-- --------------------------------------------------------

--
-- Estrutura de tabela para a tabela `salas`
--

CREATE TABLE `salas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `mesas` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura de tabela para la tabela `temp_pedidos`
--

CREATE TABLE `temp_pedidos` (
  `id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura de tabela para a tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `rol` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `rol`, `estado`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin', 1, 1);


--
-- Indices da tabela `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indices da tabela `detalhe_pedidos`
--
ALTER TABLE `detalhe_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices da tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sala` (`id_sala`);

--
-- Indices de la tabla `pratos`
--
ALTER TABLE `pratos`
  ADD PRIMARY KEY (`id`);

--
-- Indices da tabela `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`);

--
-- Indices da tabela `temp_pedidos`
--
ALTER TABLE `temp_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices da tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT da tabela `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT da tabela `detalhe_pedidos`
--
ALTER TABLE `detalhe_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT da tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT da tabela `pratos`
--
ALTER TABLE `pratos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT da tabela `salas`
--
ALTER TABLE `salas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT da tabela `temp_pedidos`
--
ALTER TABLE `temp_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT da tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- FK para a tabela `detale_pedidos`
--
ALTER TABLE `detalhe_pedidos`
  ADD CONSTRAINT `detalhe_pedidos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`);

--
-- FK para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
