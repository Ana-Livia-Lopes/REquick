-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 10:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bd_requick`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_empresa`
--

CREATE TABLE `tb_empresa` (
  `id` int(11) NOT NULL,
  `nome_empresa` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_empresa`
--

INSERT INTO `tb_empresa` (`id`, `nome_empresa`, `cnpj`) VALUES
(1, 'Tech Solutions Ltda', '12.345.678/0001-90'),
(2, 'Inova Soft', '98.765.432/0001-21');

-- --------------------------------------------------------

--
-- Table structure for table `tb_historico`
--

CREATE TABLE `tb_historico` (
  `id` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `modificacao` text NOT NULL,
  `autor` int(11) DEFAULT NULL,
  `id_requisito` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_historico`
--

INSERT INTO `tb_historico` (`id`, `data`, `modificacao`, `autor`, `id_requisito`) VALUES
(1, '2026-05-01 15:43:35', 'Criação inicial do requisito de biometria', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_imagens_projeto`
--

CREATE TABLE `tb_imagens_projeto` (
  `id` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `caminho` varchar(500) NOT NULL,
  `data_upload` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_projetos`
--

CREATE TABLE `tb_projetos` (
  `id` int(11) NOT NULL,
  `nome_projeto` varchar(150) NOT NULL,
  `data_criacao` date NOT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `status_projeto` tinyint(1) NOT NULL DEFAULT 0,
  `escopo_inicial` text NOT NULL DEFAULT 'não definido'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_projetos`
--

INSERT INTO `tb_projetos` (`id`, `nome_projeto`, `data_criacao`, `id_empresa`, `descricao`, `status_projeto`, `escopo_inicial`) VALUES
(1, 'Sistema ERP v2', '2023-10-01', 1, NULL, 0, 'não definido'),
(2, 'App Mobile Cliente', '2023-11-15', 1, NULL, 0, 'não definido'),
(3, 'Portal de Intranet', '2024-01-10', 2, NULL, 0, 'não definido');

-- --------------------------------------------------------

--
-- Table structure for table `tb_requisitos`
--

CREATE TABLE `tb_requisitos` (
  `id` int(11) NOT NULL,
  `titulo_requisito` varchar(255) NOT NULL,
  `descricao_requisito` text DEFAULT NULL,
  `tipo` enum('Funcional','Nao Funcional') NOT NULL,
  `prioridade` varchar(20) DEFAULT NULL,
  `responsavel` varchar(100) DEFAULT NULL,
  `autor` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_projeto` int(11) DEFAULT NULL,
  `status_req` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_requisitos`
--

INSERT INTO `tb_requisitos` (`id`, `titulo_requisito`, `descricao_requisito`, `tipo`, `prioridade`, `responsavel`, `autor`, `data_modificacao`, `id_projeto`, `status_req`) VALUES
(1, 'Login via Biometria', NULL, 'Funcional', NULL, NULL, NULL, '2026-06-07 13:03:34', 1, 0),
(2, 'Tempo de resposta < 2s', NULL, 'Nao Funcional', NULL, NULL, NULL, '2026-06-07 13:03:34', 1, 0),
(3, 'Exportação de Relatórios', NULL, 'Funcional', NULL, NULL, NULL, '2026-06-07 13:03:34', 3, 0),
(4, 'teste', '', 'Funcional', 'Alta', 'teste', 'teste', '2026-06-07 13:39:18', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_usuarios`
--

CREATE TABLE `tb_usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tipo_usuario` varchar(50) DEFAULT NULL,
  `especializacao` varchar(100) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id`, `nome`, `email`, `tipo_usuario`, `especializacao`, `senha`, `id_empresa`, `status`) VALUES
(1, 'admin', 'admin@tech.com', 'Administrador', 'Administracao', '$2y$10$VQEiJ/e5eZu5TTGaL/p33uzkHmW/2NpGtkwAY74mVBlNy4QE6IBbG', 1, 'ativo'),
(3, 'Carlos Prado', 'carlos@inova.com', 'Funcionario', 'Suporte Técnico', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 2, 'inativo'),
(4, 'Aldair', 'aldair@email.com', 'Cliente', 'Desenvolvedor', '$2y$10$.JdYtbixlLOeNIyQ9jR1s.i831rRFZQw6s5GECiGmj0AmVXvIrnRG', 1, 'ativo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_empresa`
--
ALTER TABLE `tb_empresa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Indexes for table `tb_historico`
--
ALTER TABLE `tb_historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_historico_autor` (`autor`),
  ADD KEY `fk_historico_requisito` (`id_requisito`);

--
-- Indexes for table `tb_imagens_projeto`
--
ALTER TABLE `tb_imagens_projeto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_projeto` (`id_projeto`);

--
-- Indexes for table `tb_projetos`
--
ALTER TABLE `tb_projetos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_projeto_empresa` (`id_empresa`);

--
-- Indexes for table `tb_requisitos`
--
ALTER TABLE `tb_requisitos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_requisito_projeto` (`id_projeto`);

--
-- Indexes for table `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuario_empresa` (`id_empresa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_empresa`
--
ALTER TABLE `tb_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_historico`
--
ALTER TABLE `tb_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_imagens_projeto`
--
ALTER TABLE `tb_imagens_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_projetos`
--
ALTER TABLE `tb_projetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_requisitos`
--
ALTER TABLE `tb_requisitos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_historico`
--
ALTER TABLE `tb_historico`
  ADD CONSTRAINT `fk_historico_autor` FOREIGN KEY (`autor`) REFERENCES `tb_usuarios` (`id`),
  ADD CONSTRAINT `fk_historico_requisito` FOREIGN KEY (`id_requisito`) REFERENCES `tb_requisitos` (`id`);

--
-- Constraints for table `tb_imagens_projeto`
--
ALTER TABLE `tb_imagens_projeto`
  ADD CONSTRAINT `tb_imagens_projeto_ibfk_1` FOREIGN KEY (`id_projeto`) REFERENCES `tb_projetos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_projetos`
--
ALTER TABLE `tb_projetos`
  ADD CONSTRAINT `fk_projeto_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id`);

--
-- Constraints for table `tb_requisitos`
--
ALTER TABLE `tb_requisitos`
  ADD CONSTRAINT `fk_requisito_projeto` FOREIGN KEY (`id_projeto`) REFERENCES `tb_projetos` (`id`);

--
-- Constraints for table `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD CONSTRAINT `fk_usuario_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
