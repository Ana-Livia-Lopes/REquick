-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/06/2026 às 15:31
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_requick`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_comentarios`
--

CREATE TABLE `tb_comentarios` (
  `id` int(11) NOT NULL,
  `titulo_comentario` varchar(255) NOT NULL,
  `descricao_comentario` text NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_comentario` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_comentarios`
--

INSERT INTO `tb_comentarios` (`id`, `titulo_comentario`, `descricao_comentario`, `id_projeto`, `id_usuario`, `data_comentario`) VALUES
(1, 'Teste', 'testando', 2, 4, '2026-06-08 10:19:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_empresa`
--

CREATE TABLE `tb_empresa` (
  `id` int(11) NOT NULL,
  `nome_empresa` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `tb_empresa`
--

INSERT INTO `tb_empresa` (`id`, `nome_empresa`, `cnpj`) VALUES
(1, 'Tech Solutions Ltda', '12.345.678/0001-90'),
(2, 'Inova Soft', '98.765.432/0001-21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_historico`
--

CREATE TABLE `tb_historico` (
  `id` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `modificacao` text NOT NULL,
  `autor` int(11) DEFAULT NULL,
  `id_requisito` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `tb_historico`
--

INSERT INTO `tb_historico` (`id`, `data`, `modificacao`, `autor`, `id_requisito`) VALUES
(1, '2026-05-01 15:43:35', 'Criação inicial do requisito de biometria', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_imagens_projeto`
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
-- Estrutura para tabela `tb_log_acesso_projeto`
--

CREATE TABLE `tb_log_acesso_projeto` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `data_acesso` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_log_acesso_projeto`
--

INSERT INTO `tb_log_acesso_projeto` (`id`, `id_usuario`, `id_projeto`, `data_acesso`) VALUES
(1, 1, 2, '2026-06-08 09:08:19'),
(2, 1, 1, '2026-06-08 09:08:34'),
(3, 1, 1, '2026-06-08 09:09:26'),
(4, 4, 2, '2026-06-08 09:13:06'),
(5, 1, 5, '2026-06-08 09:32:05'),
(6, 1, 5, '2026-06-08 09:34:45'),
(7, 1, 1, '2026-06-08 09:46:54'),
(8, 1, 1, '2026-06-08 10:08:10'),
(9, 1, 1, '2026-06-08 10:15:22'),
(10, 1, 1, '2026-06-08 10:15:24'),
(11, 1, 1, '2026-06-08 10:15:24'),
(12, 1, 1, '2026-06-08 10:15:24'),
(13, 1, 1, '2026-06-08 10:15:25'),
(14, 4, 2, '2026-06-08 10:15:35'),
(15, 1, 5, '2026-06-08 10:18:52'),
(16, 1, 5, '2026-06-08 10:19:17'),
(17, 4, 2, '2026-06-08 10:19:34'),
(18, 4, 2, '2026-06-08 10:19:45'),
(19, 4, 2, '2026-06-08 10:19:45'),
(20, 4, 2, '2026-06-08 10:19:48'),
(21, 4, 2, '2026-06-08 10:20:36'),
(22, 4, 2, '2026-06-08 10:20:41'),
(23, 4, 2, '2026-06-08 10:21:08'),
(24, 4, 2, '2026-06-08 10:21:09'),
(25, 4, 2, '2026-06-08 10:21:12'),
(26, 1, 5, '2026-06-08 10:23:34'),
(27, 1, 2, '2026-06-08 10:23:39'),
(28, 1, 2, '2026-06-08 10:23:44'),
(29, 1, 2, '2026-06-08 10:23:48'),
(30, 1, 1, '2026-06-08 10:23:59'),
(31, 1, 1, '2026-06-08 10:24:55'),
(32, 1, 5, '2026-06-08 10:26:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_projetos`
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
-- Despejando dados para a tabela `tb_projetos`
--

INSERT INTO `tb_projetos` (`id`, `nome_projeto`, `data_criacao`, `id_empresa`, `descricao`, `status_projeto`, `escopo_inicial`) VALUES
(1, 'Sistema ERP v2', '2023-10-01', 1, NULL, 0, 'não definido'),
(2, 'App Mobile Cliente', '2023-11-15', 1, NULL, 0, 'não definido'),
(3, 'Portal de Intranet', '2024-01-10', 2, NULL, 0, 'não definido'),
(4, 'Teste', '2026-06-08', 2, 'teste', 0, 'não definido'),
(5, 'Assim', '2026-06-08', 2, 'Testando', 0, 'não definido');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_requisitos`
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
-- Despejando dados para a tabela `tb_requisitos`
--

INSERT INTO `tb_requisitos` (`id`, `titulo_requisito`, `descricao_requisito`, `tipo`, `prioridade`, `responsavel`, `autor`, `data_modificacao`, `id_projeto`, `status_req`) VALUES
(1, 'Login via Biometria', '', 'Funcional', '', '', '', '2026-06-08 09:00:05', 1, 1),
(2, 'Tempo de resposta < 2s', NULL, 'Nao Funcional', NULL, NULL, NULL, '2026-06-07 13:03:34', 1, 0),
(3, 'Exportação de Relatórios', NULL, 'Funcional', NULL, NULL, NULL, '2026-06-07 13:03:34', 3, 0),
(4, 'sodhg', '', 'Funcional', 'Alta', 'teste', 'teste', '2026-06-08 10:23:48', 2, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_usuarios`
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
-- Despejando dados para a tabela `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id`, `nome`, `email`, `tipo_usuario`, `especializacao`, `senha`, `id_empresa`, `status`) VALUES
(1, 'admin', 'admin@tech.com', 'Administrador', 'Administracao', '$2y$10$VQEiJ/e5eZu5TTGaL/p33uzkHmW/2NpGtkwAY74mVBlNy4QE6IBbG', 1, 'ativo'),
(3, 'Carlos Prado', 'carlos@inova.com', 'Desenvolvedor', 'Suporte Técnico', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 2, 'inativo'),
(4, 'Aldair', 'aldair@email.com', 'Cliente', 'Desenvolvedor', '$2y$10$.JdYtbixlLOeNIyQ9jR1s.i831rRFZQw6s5GECiGmj0AmVXvIrnRG', 1, 'ativo');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_comentarios`
--
ALTER TABLE `tb_comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comentario_projeto` (`id_projeto`),
  ADD KEY `fk_comentario_usuario` (`id_usuario`);

--
-- Índices de tabela `tb_empresa`
--
ALTER TABLE `tb_empresa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Índices de tabela `tb_historico`
--
ALTER TABLE `tb_historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_historico_autor` (`autor`),
  ADD KEY `fk_historico_requisito` (`id_requisito`);

--
-- Índices de tabela `tb_imagens_projeto`
--
ALTER TABLE `tb_imagens_projeto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_projeto` (`id_projeto`);

--
-- Índices de tabela `tb_log_acesso_projeto`
--
ALTER TABLE `tb_log_acesso_projeto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_usuario` (`id_usuario`),
  ADD KEY `fk_log_projeto` (`id_projeto`);

--
-- Índices de tabela `tb_projetos`
--
ALTER TABLE `tb_projetos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_projeto_empresa` (`id_empresa`);

--
-- Índices de tabela `tb_requisitos`
--
ALTER TABLE `tb_requisitos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_requisito_projeto` (`id_projeto`);

--
-- Índices de tabela `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuario_empresa` (`id_empresa`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_comentarios`
--
ALTER TABLE `tb_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_empresa`
--
ALTER TABLE `tb_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_historico`
--
ALTER TABLE `tb_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_imagens_projeto`
--
ALTER TABLE `tb_imagens_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_log_acesso_projeto`
--
ALTER TABLE `tb_log_acesso_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `tb_projetos`
--
ALTER TABLE `tb_projetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tb_requisitos`
--
ALTER TABLE `tb_requisitos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_comentarios`
--
ALTER TABLE `tb_comentarios`
  ADD CONSTRAINT `fk_comentario_projeto` FOREIGN KEY (`id_projeto`) REFERENCES `tb_projetos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comentario_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tb_historico`
--
ALTER TABLE `tb_historico`
  ADD CONSTRAINT `fk_historico_autor` FOREIGN KEY (`autor`) REFERENCES `tb_usuarios` (`id`),
  ADD CONSTRAINT `fk_historico_requisito` FOREIGN KEY (`id_requisito`) REFERENCES `tb_requisitos` (`id`);

--
-- Restrições para tabelas `tb_imagens_projeto`
--
ALTER TABLE `tb_imagens_projeto`
  ADD CONSTRAINT `tb_imagens_projeto_ibfk_1` FOREIGN KEY (`id_projeto`) REFERENCES `tb_projetos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tb_log_acesso_projeto`
--
ALTER TABLE `tb_log_acesso_projeto`
  ADD CONSTRAINT `fk_log_projeto` FOREIGN KEY (`id_projeto`) REFERENCES `tb_projetos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_log_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tb_projetos`
--
ALTER TABLE `tb_projetos`
  ADD CONSTRAINT `fk_projeto_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id`);

--
-- Restrições para tabelas `tb_requisitos`
--
ALTER TABLE `tb_requisitos`
  ADD CONSTRAINT `fk_requisito_projeto` FOREIGN KEY (`id_projeto`) REFERENCES `tb_projetos` (`id`);

--
-- Restrições para tabelas `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD CONSTRAINT `fk_usuario_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
