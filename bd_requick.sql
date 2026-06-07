-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16/05/2026 às 18:23
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
(1, '2026-05-01 15:43:35', 'Criação inicial do requisito de biometria', 1, 1),
(2, '2026-05-01 15:43:35', 'Atualização do critério de performance', 2, 2);

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
(3, 'Portal de Intranet', '2024-01-10', 2, NULL, 0, 'não definido');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_requisitos`
--

CREATE TABLE `tb_requisitos` (
  `id` int(11) NOT NULL,
  `nome_requisito` varchar(255) NOT NULL,
  `tipo` enum('Funcional','Nao Funcional') NOT NULL,
  `id_projeto` int(11) DEFAULT NULL,
  `status_req` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `tb_requisitos`
--

INSERT INTO `tb_requisitos` (`id`, `nome_requisito`, `tipo`, `id_projeto`, `status_req`) VALUES
(1, 'Login via Biometria', 'Funcional', 1, 0),
(2, 'Tempo de resposta < 2s', 'Nao Funcional', 1, 0),
(3, 'Exportação de Relatórios', 'Funcional', 3, 0);

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
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id`, `nome`, `email`, `tipo_usuario`, `especializacao`, `senha`, `id_empresa`) VALUES
(1, 'admin', 'admin@tech.com', 'Administrador', 'Administracao', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 1),
(2, 'dev', 'dev@tech.com', 'Desenvolvedor', 'Desenvolvedor', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 1),
(3, 'Carlos Prado', 'carlos@inova.com', 'Funcionario', 'Suporte Técnico', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 2);

--
-- Índices para tabelas despejadas
--

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
-- AUTO_INCREMENT de tabela `tb_projetos`
--
ALTER TABLE `tb_projetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_requisitos`
--
ALTER TABLE `tb_requisitos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_historico`
--
ALTER TABLE `tb_historico`
  ADD CONSTRAINT `fk_historico_autor` FOREIGN KEY (`autor`) REFERENCES `tb_usuarios` (`id`),
  ADD CONSTRAINT `fk_historico_requisito` FOREIGN KEY (`id_requisito`) REFERENCES `tb_requisitos` (`id`);

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
