-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/06/2026 às 02:45
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

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
(1, 'Tech Solutions Ltda', '12.345.678/0001-91'),
(2, 'Inova Soft', '98.765.432/0001-21'),
(3, 'Mercado Tech', '76.347.293/0001-74');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_historico`
--

CREATE TABLE `tb_historico` (
  `id` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `modificacao` text NOT NULL,
  `autor` int(11) DEFAULT NULL,
  `id_requisito` int(11) DEFAULT NULL,
  `id_projeto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `tb_historico`
--

INSERT INTO `tb_historico` (`id`, `data`, `modificacao`, `autor`, `id_requisito`, `id_projeto`) VALUES
(1, '2026-05-01 15:43:35', 'Criação inicial do requisito de biometria', 1, 1, NULL),
(3, '2026-06-11 21:05:07', 'Marcou o requisito como \'Em andamento\'', NULL, 1, NULL),
(4, '2026-06-11 21:05:08', 'Validou o requisito', NULL, 2, NULL),
(5, '2026-06-11 21:05:12', 'Validou o requisito', NULL, 1, NULL),
(6, '2026-06-11 21:05:16', 'Marcou o requisito como \'Em andamento\'', NULL, 2, NULL),
(7, '2026-06-11 21:05:22', 'Validou o requisito', NULL, 2, NULL),
(8, '2026-06-11 21:05:49', 'Marcou o requisito como \'Em andamento\'', NULL, 1, NULL),
(9, '2026-06-11 21:11:09', 'Validou o requisito', NULL, 3, NULL),
(10, '2026-06-11 21:11:39', 'Convidou o(a) usuário(a) Koba para o projeto', 1, NULL, 3),
(11, '2026-06-11 21:19:18', 'Convidou o(a) usuário(a) Koba para o projeto', 1, NULL, 6),
(12, '2026-06-11 21:26:41', 'Convidou o(a) usuário(a) Koba para o projeto', 1, NULL, 3),
(13, '2026-06-11 21:32:30', 'Convidou o(a) usuário(a) Koba para o projeto', 1, NULL, 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_imagens_projeto`
--

CREATE TABLE `tb_imagens_projeto` (
  `id` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `titulo_imagem` varchar(255) NOT NULL DEFAULT '',
  `tipo_diagrama` varchar(100) NOT NULL DEFAULT '',
  `data_upload` datetime NOT NULL DEFAULT current_timestamp(),
  `dados` mediumtext NOT NULL
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
(32, 1, 5, '2026-06-08 10:26:56'),
(33, 1, 5, '2026-06-10 20:08:15'),
(34, 1, 5, '2026-06-10 20:46:00'),
(35, 1, 5, '2026-06-10 20:46:03'),
(36, 1, 5, '2026-06-10 20:46:04'),
(37, 1, 5, '2026-06-10 20:46:04'),
(38, 1, 5, '2026-06-10 20:46:04'),
(39, 1, 5, '2026-06-10 20:46:04'),
(40, 1, 5, '2026-06-10 20:46:20'),
(41, 1, 5, '2026-06-10 20:46:24'),
(42, 1, 5, '2026-06-10 20:46:28'),
(43, 1, 5, '2026-06-10 20:46:39'),
(44, 1, 5, '2026-06-10 20:46:42'),
(45, 1, 5, '2026-06-10 20:46:45'),
(46, 1, 5, '2026-06-10 20:46:49'),
(47, 1, 6, '2026-06-10 20:53:08'),
(48, 1, 6, '2026-06-10 20:54:30'),
(49, 1, 6, '2026-06-10 20:55:34'),
(50, 1, 6, '2026-06-10 20:56:46'),
(51, 1, 6, '2026-06-10 20:56:57'),
(52, 1, 6, '2026-06-10 20:57:08'),
(53, 1, 6, '2026-06-10 20:57:19'),
(54, 1, 6, '2026-06-10 20:59:24'),
(55, 1, 6, '2026-06-10 21:00:25'),
(56, 1, 6, '2026-06-10 21:01:45'),
(57, 1, 6, '2026-06-10 21:02:03'),
(58, 1, 6, '2026-06-10 21:02:14'),
(59, 1, 6, '2026-06-10 21:02:23'),
(60, 1, 6, '2026-06-10 21:04:32'),
(61, 1, 6, '2026-06-10 21:04:37'),
(62, 1, 6, '2026-06-10 21:04:46'),
(63, 1, 6, '2026-06-10 21:06:57'),
(64, 1, 6, '2026-06-10 21:07:02'),
(65, 1, 6, '2026-06-10 21:07:10'),
(66, 1, 6, '2026-06-10 21:08:16'),
(67, 1, 6, '2026-06-10 21:10:04'),
(68, 1, 6, '2026-06-10 21:10:31'),
(69, 1, 6, '2026-06-10 21:10:46'),
(70, 1, 6, '2026-06-10 21:11:02'),
(71, 1, 5, '2026-06-10 21:14:03'),
(72, 1, 5, '2026-06-10 21:14:32'),
(73, 1, 5, '2026-06-10 21:14:44'),
(74, 1, 6, '2026-06-10 21:19:54'),
(75, 1, 6, '2026-06-11 19:10:45'),
(76, 1, 6, '2026-06-11 19:11:40'),
(77, 1, 6, '2026-06-11 19:24:15'),
(78, 1, 6, '2026-06-11 19:24:59'),
(79, 1, 6, '2026-06-11 19:25:10'),
(80, 1, 6, '2026-06-11 19:25:40'),
(81, 1, 6, '2026-06-11 19:26:28'),
(82, 1, 6, '2026-06-11 19:26:31'),
(83, 1, 6, '2026-06-11 19:27:05'),
(84, 1, 6, '2026-06-11 19:27:07'),
(85, 1, 6, '2026-06-11 19:27:09'),
(86, 1, 6, '2026-06-11 19:27:32'),
(87, 1, 6, '2026-06-11 19:28:08'),
(88, 1, 7, '2026-06-11 19:28:27'),
(89, 1, 7, '2026-06-11 19:42:13'),
(90, 1, 7, '2026-06-11 19:43:22'),
(91, 1, 7, '2026-06-11 19:43:41'),
(92, 1, 7, '2026-06-11 19:43:58'),
(93, 1, 7, '2026-06-11 19:44:29'),
(94, 1, 7, '2026-06-11 19:44:59'),
(95, 1, 7, '2026-06-11 19:44:59'),
(96, 1, 7, '2026-06-11 19:45:21'),
(97, 1, 7, '2026-06-11 19:55:00'),
(98, 1, 7, '2026-06-11 19:55:48'),
(99, 1, 7, '2026-06-11 19:56:05'),
(100, 1, 7, '2026-06-11 19:56:24'),
(101, 1, 7, '2026-06-11 19:56:35'),
(102, 1, 7, '2026-06-11 19:56:54'),
(103, 5, 2, '2026-06-11 20:01:57'),
(104, 5, 2, '2026-06-11 20:02:20'),
(105, 6, 2, '2026-06-11 20:05:48'),
(106, 1, 7, '2026-06-11 20:07:37'),
(107, 1, 2, '2026-06-11 20:07:41'),
(108, 1, 2, '2026-06-11 20:07:42'),
(109, 1, 2, '2026-06-11 20:07:46'),
(110, 1, 2, '2026-06-11 20:09:21'),
(111, 1, 2, '2026-06-11 20:09:30'),
(112, 1, 2, '2026-06-11 20:09:35'),
(113, 1, 2, '2026-06-11 20:09:46'),
(114, 1, 2, '2026-06-11 20:10:14'),
(115, 1, 2, '2026-06-11 20:10:21'),
(116, 1, 1, '2026-06-11 20:10:29'),
(117, 1, 1, '2026-06-11 20:10:33'),
(118, 1, 1, '2026-06-11 20:10:36'),
(119, 1, 1, '2026-06-11 20:10:38'),
(120, 1, 1, '2026-06-11 20:10:41'),
(121, 1, 1, '2026-06-11 20:10:45'),
(122, 1, 1, '2026-06-11 20:10:48'),
(123, 1, 1, '2026-06-11 20:10:50'),
(124, 1, 1, '2026-06-11 20:10:59'),
(125, 1, 1, '2026-06-11 20:11:07'),
(126, 1, 1, '2026-06-11 20:11:48'),
(127, 1, 1, '2026-06-11 21:05:03'),
(128, 1, 1, '2026-06-11 21:05:07'),
(129, 1, 1, '2026-06-11 21:05:08'),
(130, 1, 1, '2026-06-11 21:05:12'),
(131, 1, 1, '2026-06-11 21:05:16'),
(132, 1, 1, '2026-06-11 21:05:22'),
(133, 1, 1, '2026-06-11 21:05:27'),
(134, 1, 1, '2026-06-11 21:05:28'),
(135, 1, 1, '2026-06-11 21:05:28'),
(136, 1, 1, '2026-06-11 21:05:29'),
(137, 1, 1, '2026-06-11 21:05:29'),
(138, 1, 1, '2026-06-11 21:05:49'),
(139, 1, 1, '2026-06-11 21:05:53'),
(140, 1, 1, '2026-06-11 21:05:54'),
(141, 1, 1, '2026-06-11 21:05:59'),
(142, 1, 1, '2026-06-11 21:06:02'),
(143, 1, 1, '2026-06-11 21:06:06'),
(144, 1, 1, '2026-06-11 21:06:08'),
(145, 1, 1, '2026-06-11 21:06:09'),
(146, 1, 1, '2026-06-11 21:06:10'),
(147, 1, 1, '2026-06-11 21:06:16'),
(148, 1, 1, '2026-06-11 21:06:20'),
(149, 1, 1, '2026-06-11 21:06:21'),
(150, 1, 1, '2026-06-11 21:06:22'),
(151, 1, 2, '2026-06-11 21:06:27'),
(152, 1, 2, '2026-06-11 21:06:29'),
(153, 1, 2, '2026-06-11 21:06:32'),
(154, 1, 2, '2026-06-11 21:06:34'),
(155, 1, 2, '2026-06-11 21:06:35'),
(156, 1, 2, '2026-06-11 21:06:35'),
(157, 1, 2, '2026-06-11 21:06:35'),
(158, 1, 3, '2026-06-11 21:07:05'),
(159, 1, 3, '2026-06-11 21:07:15'),
(160, 1, 3, '2026-06-11 21:11:07'),
(161, 1, 3, '2026-06-11 21:11:09'),
(162, 1, 3, '2026-06-11 21:11:10'),
(163, 1, 3, '2026-06-11 21:11:39'),
(164, 1, 3, '2026-06-11 21:12:19'),
(165, 1, 3, '2026-06-11 21:12:26'),
(166, 1, 3, '2026-06-11 21:12:38'),
(167, 1, 3, '2026-06-11 21:16:38'),
(168, 1, 3, '2026-06-11 21:16:40'),
(169, 1, 6, '2026-06-11 21:19:12'),
(170, 1, 6, '2026-06-11 21:19:18'),
(171, 1, 3, '2026-06-11 21:20:32'),
(172, 1, 3, '2026-06-11 21:20:35'),
(173, 1, 3, '2026-06-11 21:20:38'),
(174, 1, 3, '2026-06-11 21:20:49'),
(175, 1, 3, '2026-06-11 21:20:51'),
(176, 1, 3, '2026-06-11 21:20:53'),
(177, 1, 3, '2026-06-11 21:22:38'),
(178, 1, 3, '2026-06-11 21:22:48'),
(179, 1, 3, '2026-06-11 21:24:08'),
(180, 1, 3, '2026-06-11 21:26:31'),
(181, 1, 3, '2026-06-11 21:26:41'),
(182, 1, 7, '2026-06-11 21:32:26'),
(183, 1, 7, '2026-06-11 21:32:30'),
(184, 1, 3, '2026-06-11 21:32:33'),
(185, 1, 3, '2026-06-11 21:32:38');

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
(5, 'Assim', '2026-06-08', 2, 'Testando', 0, 'não definido'),
(6, 'Teste', '2026-06-26', 3, 'Testando', 0, 'não definido'),
(7, 'Projeto 1', '2026-06-18', 3, 'asd', 0, 'não definido');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_projeto_usuarios`
--

CREATE TABLE `tb_projeto_usuarios` (
  `id_projeto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_projeto_usuarios`
--

INSERT INTO `tb_projeto_usuarios` (`id_projeto`, `id_usuario`) VALUES
(3, 5),
(7, 5);

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
(1, 'Login via Biometria', 'asd', 'Nao Funcional', '', '', '', '2026-06-11 21:06:16', 1, 0),
(2, 'Tempo de resposta <', 'asda', 'Nao Funcional', '', '', '', '2026-06-11 21:05:22', 1, 1),
(3, 'Exportação de Relatórios', 'asd', 'Funcional', '', '', '', '2026-06-11 21:20:38', 3, 1),
(4, 'sodhg', 'a', 'Funcional', 'Alta', 'teste', 'teste', '2026-06-11 21:06:32', 2, 1),
(7, 'RF01', 'as', 'Funcional', 'Média', 'Vc', 'admin', '2026-06-11 19:27:07', 6, 0),
(8, 'sodhg', 'asd', 'Funcional', '', 'teste', 'admin', '2026-06-11 21:20:49', 3, 0);

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
(1, 'admin', 'admin@tech.com', 'Administrador', 'Administracao', '$2y$10$SkzpgRTl3xkdmfn/gzIhkOEJTjxv2CE.E9vaD8IgMcCzABSMUQO16', 1, 'ativo'),
(3, 'Carlos Prado', 'carlos@inova.com', 'Desenvolvedor', 'Suporte Técnico', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 2, 'inativo'),
(4, 'Aldair', 'aldair@email.com', 'Cliente', 'Desenvolvedor', '$2y$10$.JdYtbixlLOeNIyQ9jR1s.i831rRFZQw6s5GECiGmj0AmVXvIrnRG', 1, 'ativo'),
(5, 'Koba', 'koba@email.com', 'Funcionario', 'Desenvolvedor', '$2y$10$9z49tGrUFedLTlloY.E2YuhPpDXHnhT9pYeFM8DBGEC1IcHMA0S1.', 1, 'ativo'),
(6, 'João', 'joao@email.com', 'Cliente', 'Cliente', '$2y$10$aywZKOn4HatkqVkFTL6s/.qo9a1na2Q9pfjqJnDZJ9FBNhHFLvGL6', 1, 'ativo');

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
-- Índices de tabela `tb_projeto_usuarios`
--
ALTER TABLE `tb_projeto_usuarios`
  ADD PRIMARY KEY (`id_projeto`,`id_usuario`),
  ADD KEY `fk_usuario_convite` (`id_usuario`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_historico`
--
ALTER TABLE `tb_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tb_imagens_projeto`
--
ALTER TABLE `tb_imagens_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_log_acesso_projeto`
--
ALTER TABLE `tb_log_acesso_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT de tabela `tb_projetos`
--
ALTER TABLE `tb_projetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tb_requisitos`
--
ALTER TABLE `tb_requisitos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Restrições para tabelas `tb_projeto_usuarios`
--
ALTER TABLE `tb_projeto_usuarios`
  ADD CONSTRAINT `fk_projeto_convite` FOREIGN KEY (`id_projeto`) REFERENCES `tb_projetos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_convite` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
