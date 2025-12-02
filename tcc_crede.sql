-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.3.90:3306
-- Tempo de geração: 31-Out-2025 às 10:55
-- Versão do servidor: 8.0.43-0ubuntu0.24.04.1
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc_crede`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblArquivo`
--

CREATE TABLE `tblArquivo` (
  `id` int NOT NULL,
  `idReferencia` int NOT NULL,
  `tipoReferencia` enum('PESSOA','EMPRESA','EVENTO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pathLocal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pathNuvem` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observacao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipoArquivo` enum('AVATAR','DOCUMENTO','LOGO','BANNER','OUTROS') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `arqSincronizadaNuvem` tinyint(1) NOT NULL DEFAULT '0',
  `arqSincronizadaLeitores` tinyint(1) NOT NULL DEFAULT '0',
  `deviceId` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataAtualizacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblCredencial`
--

CREATE TABLE `tblCredencial` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `idLote` char(36) NOT NULL,
  `idPessoa` char(36) DEFAULT NULL,
  `idUsuarioCadastro` int DEFAULT NULL,
  `docPessoa` varchar(255) DEFAULT NULL,
  `nomeCredencial` varchar(100) NOT NULL,
  `codigoCredencial` text NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `detalhes` varchar(255) DEFAULT NULL,
  `status` enum('T','F') DEFAULT 'T',
  `impresso` enum('T','F') DEFAULT 'F',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataImpressao` timestamp NULL DEFAULT NULL,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblCredencialPeriodo`
--

CREATE TABLE `tblCredencialPeriodo` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `idCredencial` char(36) NOT NULL,
  `dataInicio` date NOT NULL,
  `dataTermino` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaTermino` time NOT NULL,
  `status` enum('T','F') DEFAULT 'T',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblDocumento`
--

CREATE TABLE `tblDocumento` (
  `id` int NOT NULL,
  `idPessoa` int DEFAULT NULL,
  `tipo` enum('RG','CPF','Passaporte','Outros','CNH','CNPJ','IE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `documento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `detalhes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'F',
  `dataCriacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblEmail`
--

CREATE TABLE `tblEmail` (
  `id` int NOT NULL,
  `idPessoa` int DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'F',
  `dataCriacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tblEmail`
--

INSERT INTO `tblEmail` (`id`, `idPessoa`, `email`, `status`, `dataCriacao`, `dataEdicao`) VALUES
(1, 1, 'superadmin@gmail.com', 'T', '2025-10-31 00:05:49', '2025-10-31 06:23:59'),
(2, 2, 'admin@gmail.com', 'T', '2025-10-31 00:05:49', '2025-10-31 06:23:59'),
(3, 3, 'usuario@gmail.com', 'T', '2025-10-31 00:05:49', '2025-10-31 06:23:59');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblEndereco`
--

CREATE TABLE `tblEndereco` (
  `id` int NOT NULL,
  `idPessoa` int DEFAULT NULL,
  `idEmpresa` int DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cep` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numero` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `complemento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zona` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bairro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cidade` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `longitude` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'F',
  `dataCriacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblEntradas`
--

CREATE TABLE `tblEntradas` (
  `id` int NOT NULL,
  `idEvento` int DEFAULT NULL,
  `idPessoa` char(36) DEFAULT NULL,
  `idCredencial` char(36) DEFAULT NULL,
  `idSetor` char(36) DEFAULT NULL,
  `idLeitor` char(36) DEFAULT NULL,
  `idTerminal` char(36) DEFAULT NULL,
  `credencial` text,
  `tipoEntrada` enum('ENTRADA','SAIDA') NOT NULL DEFAULT 'ENTRADA',
  `dataTentativa` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `permitida` enum('T','F') DEFAULT 'F',
  `mensagem` varchar(250) DEFAULT NULL,
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblEvento`
--

CREATE TABLE `tblEvento` (
  `id` int NOT NULL,
  `idCategoria` int NOT NULL,
  `idLocal` int DEFAULT NULL,
  `nomeEvento` varchar(100) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `dataInicio` date DEFAULT NULL,
  `dataFim` date DEFAULT NULL,
  `licenca` text,
  `dataInicioCredenciamento` date DEFAULT NULL,
  `dataFimCredenciamento` date DEFAULT NULL,
  `status` enum('T','F') DEFAULT 'F',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `aprovado` enum('T','F') DEFAULT 'F'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblEvento`
--

INSERT INTO `tblEvento` (`id`, `idCategoria`, `idLocal`, `nomeEvento`, `observacao`, `dataInicio`, `dataFim`, `licenca`, `dataInicioCredenciamento`, `dataFimCredenciamento`, `status`, `dataCadastro`, `dataEdicao`, `aprovado`) VALUES
(1, 2, 1, 'TESTE', NULL, '2025-10-30', '2025-11-07', NULL, '2025-10-30', '2025-11-07', 'T', '2025-10-31 06:57:15', '2025-10-31 06:57:20', 'T');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblEventoCategoria`
--

CREATE TABLE `tblEventoCategoria` (
  `id` int NOT NULL,
  `nomeCategoria` varchar(100) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `status` enum('T','F') DEFAULT 'F',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblEventoCategoria`
--

INSERT INTO `tblEventoCategoria` (`id`, `nomeCategoria`, `observacao`, `status`, `dataCadastro`, `dataEdicao`) VALUES
(1, 'SHOW', NULL, 'T', '2025-10-31 06:50:05', '2025-10-31 06:50:05'),
(2, 'RODEIO', NULL, 'T', '2025-10-31 06:50:05', '2025-10-31 06:50:27');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblEventoComponentes`
--

CREATE TABLE `tblEventoComponentes` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `qtdTerminais` int DEFAULT '0',
  `qtdSetores` int DEFAULT '0',
  `usaLeitorFacial` enum('S','N') DEFAULT 'N',
  `qtdLeitoresFaciais` int DEFAULT '0',
  `credenciaisImpressas` enum('S','N') DEFAULT 'N',
  `qtdLotesCredenciais` int DEFAULT '0',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `marcaLeitor` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblEventoComponentes`
--

INSERT INTO `tblEventoComponentes` (`id`, `idEvento`, `qtdTerminais`, `qtdSetores`, `usaLeitorFacial`, `qtdLeitoresFaciais`, `credenciaisImpressas`, `qtdLotesCredenciais`, `dataCadastro`, `dataEdicao`, `marcaLeitor`) VALUES
(1, 1, 5, 10, 'S', 1, 'N', 5, '2025-10-31 07:18:53', '2025-10-31 07:24:30', '{}');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblEventoLocal`
--

CREATE TABLE `tblEventoLocal` (
  `id` int NOT NULL,
  `nomeOficial` varchar(255) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'T',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblEventoLocal`
--

INSERT INTO `tblEventoLocal` (`id`, `nomeOficial`, `cep`, `logradouro`, `numero`, `bairro`, `cidade`, `estado`, `complemento`, `latitude`, `longitude`, `status`, `dataCadastro`, `dataEdicao`) VALUES
(1, 'TESTE', '14750000', 'Avenida das Pitangueiras', '41', 'Centro', 'Pitangueiras', 'SP', '', '', '', 'T', '2025-10-31 06:52:42', '2025-10-31 06:52:42');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblHistorico`
--

CREATE TABLE `tblHistorico` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `idReferencia` char(36) DEFAULT NULL,
  `tipoReferencia` enum('EVENTO','SETOR','TERMINAL','LEITOR','CREDENCIAL','LOTE') NOT NULL,
  `idUsuario` char(36) DEFAULT NULL,
  `idServidor` int DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `sql` text,
  `tipo` enum('CADASTRO','EDICAO','DESATIVACAO','ATIVACAO','APROVACAO','REPROVACAO','CANCELAMENTO','LIBERACAO','BLOQUEIO') NOT NULL,
  `ipUsuario` varchar(45) DEFAULT NULL,
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblHistoricoToken`
--

CREATE TABLE `tblHistoricoToken` (
  `id` int NOT NULL,
  `idToken` int NOT NULL,
  `acao` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observacao` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dataUso` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `idUsuario` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblLeitor`
--

CREATE TABLE `tblLeitor` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `idSetor` char(36) DEFAULT NULL,
  `idTerminal` char(36) DEFAULT NULL,
  `idPadrao` char(36) DEFAULT NULL,
  `nomeLeitor` varchar(100) DEFAULT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `ip` varchar(39) NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `deviceId` varchar(100) DEFAULT NULL,
  `serverId` varchar(100) DEFAULT NULL,
  `serverUrl` varchar(100) DEFAULT NULL,
  `session` varchar(100) DEFAULT NULL,
  `condicao` varchar(5) NOT NULL DEFAULT 'OFF',
  `status` enum('T','F') DEFAULT 'T',
  `configurado` enum('T','F') DEFAULT 'F',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblLeitor`
--

INSERT INTO `tblLeitor` (`id`, `idEvento`, `idSetor`, `idTerminal`, `idPadrao`, `nomeLeitor`, `observacao`, `ip`, `usuario`, `senha`, `deviceId`, `serverId`, `serverUrl`, `session`, `condicao`, `status`, `configurado`, `dataCadastro`, `dataEdicao`) VALUES
(1, 1, '1', '1', NULL, 'testinho', '', '192.168.3.59', 'teste', 'teste', NULL, NULL, NULL, NULL, 'OFF', 'T', 'F', '2025-10-31 07:39:28', '2025-10-31 07:39:28');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblLeitorFoto`
--

CREATE TABLE `tblLeitorFoto` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `idLeitor` char(36) NOT NULL,
  `idArquivo` char(36) NOT NULL,
  `status` int DEFAULT '1',
  `mensagem` varchar(255) DEFAULT NULL,
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblLog`
--

CREATE TABLE `tblLog` (
  `id` int NOT NULL,
  `idUsuario` int DEFAULT '0',
  `stringSQL` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `acao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `modulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'F',
  `dataCriacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblLote`
--

CREATE TABLE `tblLote` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `nomeLote` varchar(100) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `permiteDuplicidade` tinyint(1) DEFAULT '0',
  `autonumeracao` tinyint(1) DEFAULT '0',
  `numeroLetras` tinyint(1) DEFAULT '0',
  `qtdDigitos` int DEFAULT NULL,
  `tipoCodigo` varchar(50) DEFAULT NULL,
  `tipoCredencial` varchar(50) DEFAULT NULL,
  `permiteAcessoFacial` tinyint(1) DEFAULT '0',
  `permiteImpressao` tinyint(1) DEFAULT '0',
  `status` enum('T','F') DEFAULT 'T',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblLote`
--

INSERT INTO `tblLote` (`id`, `idEvento`, `nomeLote`, `observacao`, `permiteDuplicidade`, `autonumeracao`, `numeroLetras`, `qtdDigitos`, `tipoCodigo`, `tipoCredencial`, `permiteAcessoFacial`, `permiteImpressao`, `status`, `dataCadastro`, `dataEdicao`) VALUES
(1, 1, 'TESTE1', '', 0, 0, NULL, NULL, 'BARRAS_1D', 'PAPEL', 1, 0, 'T', '2025-10-31 07:40:20', '2025-10-31 07:55:37');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblLotePeriodo`
--

CREATE TABLE `tblLotePeriodo` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `idLote` char(36) NOT NULL,
  `dataInicio` date NOT NULL,
  `dataTermino` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaTermino` time NOT NULL,
  `status` enum('T','F') DEFAULT 'T',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblLotePeriodo`
--

INSERT INTO `tblLotePeriodo` (`id`, `idEvento`, `idLote`, `dataInicio`, `dataTermino`, `horaInicio`, `horaTermino`, `status`, `dataCadastro`, `dataEdicao`) VALUES
(4, 1, '1', '2025-10-30', '2025-11-06', '11:11:00', '11:11:00', 'T', '2025-10-31 07:54:51', '2025-10-31 07:54:51'),
(5, 1, '1', '2025-11-07', '2025-11-12', '22:22:00', '22:22:00', 'T', '2025-10-31 07:54:51', '2025-10-31 07:54:51');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblPermissao`
--

CREATE TABLE `tblPermissao` (
  `id` int NOT NULL,
  `permissao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'F',
  `dataCriacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tblPermissao`
--

INSERT INTO `tblPermissao` (`id`, `permissao`, `status`, `dataCriacao`, `dataEdicao`) VALUES
(1, 'SUPERADMIN', 'T', '2025-10-31 00:13:43', '2025-10-31 06:23:39'),
(2, 'ADMIN', 'T', '2025-10-31 00:13:43', '2025-10-31 00:13:43'),
(3, 'USUARIO', 'T', '2025-10-31 00:13:43', '2025-10-31 00:13:43');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblPessoa`
--

CREATE TABLE `tblPessoa` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sobrenome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apelido` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `genero` enum('Masculino','Feminino','Outros') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dataNascimento` date DEFAULT NULL,
  `observacoes` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'F',
  `dataCriacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tblPessoa`
--

INSERT INTO `tblPessoa` (`id`, `nome`, `sobrenome`, `apelido`, `genero`, `dataNascimento`, `observacoes`, `status`, `dataCriacao`, `dataEdicao`) VALUES
(1, 'SUPERADMIN', '', NULL, 'Masculino', NULL, NULL, 'T', '2025-10-31 00:12:13', '2025-10-31 06:20:48'),
(2, 'ADMIN', NULL, NULL, 'Masculino', NULL, NULL, 'T', '2025-10-31 06:21:02', '2025-10-31 06:21:02'),
(3, 'USUARIO', NULL, NULL, 'Masculino', NULL, NULL, 'T', '2025-10-31 06:21:13', '2025-10-31 06:21:13');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblRelCredencialSetor`
--

CREATE TABLE `tblRelCredencialSetor` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `idCredencial` char(36) NOT NULL,
  `idSetor` char(36) NOT NULL,
  `permiteSaida` tinyint(1) DEFAULT '0',
  `permiteReentrada` tinyint(1) DEFAULT '0',
  `tipoReentrada` int DEFAULT NULL,
  `status` enum('T','F') DEFAULT 'T',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblRelLoteSetor`
--

CREATE TABLE `tblRelLoteSetor` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `idLote` char(36) NOT NULL,
  `idSetor` char(36) NOT NULL,
  `permiteSaida` tinyint(1) DEFAULT '0',
  `permiteReentrada` tinyint(1) DEFAULT '0',
  `tipoReentrada` int DEFAULT NULL,
  `status` enum('T','F') DEFAULT 'T',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblRelLoteSetor`
--

INSERT INTO `tblRelLoteSetor` (`id`, `idEvento`, `idLote`, `idSetor`, `permiteSaida`, `permiteReentrada`, `tipoReentrada`, `status`, `dataCadastro`, `dataEdicao`) VALUES
(3, 1, '1', '1', 0, 0, NULL, 'T', '2025-10-31 07:50:09', '2025-10-31 07:50:09');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblSetor`
--

CREATE TABLE `tblSetor` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `nomeSetor` varchar(100) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `status` enum('T','F') DEFAULT 'T',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblSetor`
--

INSERT INTO `tblSetor` (`id`, `idEvento`, `nomeSetor`, `observacao`, `status`, `dataCadastro`, `dataEdicao`) VALUES
(1, 1, 'TESTE 1', NULL, 'T', '2025-10-31 07:07:41', '2025-10-31 07:07:41');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblTelefone`
--

CREATE TABLE `tblTelefone` (
  `id` int NOT NULL,
  `idPessoa` int DEFAULT NULL,
  `telefone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipo` enum('Fixo','Celular') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'F',
  `dataCriacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblTerminal`
--

CREATE TABLE `tblTerminal` (
  `id` int NOT NULL,
  `idEvento` int NOT NULL,
  `idSetor` char(36) DEFAULT NULL,
  `idPadrao` char(36) DEFAULT NULL,
  `nomeTerminal` varchar(100) DEFAULT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `tipo` enum('ENTRADA','SAIDA','AMBOS') NOT NULL DEFAULT 'ENTRADA',
  `prefixoSaida` varchar(10) DEFAULT NULL,
  `ip` varchar(39) DEFAULT NULL,
  `deviceId` varchar(255) DEFAULT NULL,
  `serverId` varchar(255) DEFAULT NULL,
  `serverUrl` varchar(255) DEFAULT NULL,
  `condicao` varchar(5) NOT NULL DEFAULT 'OFF',
  `status` enum('T','F') DEFAULT 'T',
  `dataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `tblTerminal`
--

INSERT INTO `tblTerminal` (`id`, `idEvento`, `idSetor`, `idPadrao`, `nomeTerminal`, `observacao`, `tipo`, `prefixoSaida`, `ip`, `deviceId`, `serverId`, `serverUrl`, `condicao`, `status`, `dataCadastro`, `dataEdicao`) VALUES
(1, 1, '1', NULL, 'yhfghfgsr432', NULL, 'ENTRADA', NULL, NULL, NULL, NULL, NULL, 'OFF', 'T', '2025-10-31 07:32:10', '2025-10-31 07:37:05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblToken`
--

CREATE TABLE `tblToken` (
  `id` int NOT NULL,
  `idEmpresa` int DEFAULT NULL,
  `token` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `idParceiro` int DEFAULT NULL,
  `dataCadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('CADASTRO','ACESSO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'CADASTRO',
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'T',
  `deletado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblUsuario`
--

CREATE TABLE `tblUsuario` (
  `id` int NOT NULL,
  `idPessoa` int NOT NULL,
  `idPermissao` int NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sessionID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('T','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'F',
  `dataCriacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `dataEdicao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tblUsuario`
--

INSERT INTO `tblUsuario` (`id`, `idPessoa`, `idPermissao`, `senha`, `sessionID`, `token`, `status`, `dataCriacao`, `dataEdicao`, `usuario`) VALUES
(1, 1, 1, '$2a$08$xdO4hS3Wv50tsBndFP32R.LivUWh39a0eg3h8HXCiqH7.q7P.zE4W', '0qd5r6jcjss5b001enihqvu3jb', NULL, 'T', '2025-10-31 00:13:03', '2025-10-31 09:24:52', 'super.admin'),
(2, 2, 2, '$2a$08$xdO4hS3Wv50tsBndFP32R.LivUWh39a0eg3h8HXCiqH7.q7P.zE4W', '', NULL, 'T', '2025-10-31 03:13:03', '2025-10-31 09:24:27', 'admin'),
(3, 3, 3, '$2a$08$xdO4hS3Wv50tsBndFP32R.LivUWh39a0eg3h8HXCiqH7.q7P.zE4W', '', NULL, 'T', '2025-10-31 03:21:53', '2025-10-31 09:24:40', 'usuario');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vwEventos`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vwEventos` (
`aprovado` enum('T','F')
,`dataCadastroEvento` timestamp
,`dataEdicaoEvento` timestamp
,`dataFim` date
,`dataFimCredenciamento` date
,`dataInicio` date
,`dataInicioCredenciamento` date
,`idCategoria` int
,`idEvento` int
,`idLocal` int
,`licenca` text
,`nomeCategoria` varchar(100)
,`nomeEvento` varchar(100)
,`nomeLocal` varchar(255)
,`observacaoEvento` varchar(255)
,`statusEvento` enum('T','F')
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vwLogin`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vwLogin` (
`apelido` varchar(255)
,`cpf` varchar(255)
,`email` varchar(255)
,`id` int
,`idPermissao` bigint
,`idUsuario` int
,`nome` varchar(255)
,`permissao` varchar(255)
,`senha` varchar(255)
,`sessionID` varchar(255)
,`sobrenome` varchar(255)
,`STATUS` enum('T','F')
,`telefone` varchar(50)
,`usuario` varchar(100)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vwUsuarios`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vwUsuarios` (
`apelido` varchar(255)
,`dataCriacao` datetime
,`dataEdicao` datetime
,`email` varchar(255)
,`idPermissao` int
,`idPessoa` int
,`idUsuario` int
,`nome` varchar(255)
,`permissao` varchar(255)
,`senha` varchar(255)
,`sessionID` varchar(255)
,`sobrenome` varchar(255)
,`status` enum('T','F')
,`token` varchar(100)
,`usuario` varchar(100)
);

-- --------------------------------------------------------

--
-- Estrutura para vista `vwEventos`
--
DROP TABLE IF EXISTS `vwEventos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`crede`@`%` SQL SECURITY DEFINER VIEW `vwEventos`  AS SELECT `e`.`id` AS `idEvento`, `e`.`nomeEvento` AS `nomeEvento`, `e`.`observacao` AS `observacaoEvento`, `e`.`dataInicio` AS `dataInicio`, `e`.`dataFim` AS `dataFim`, `e`.`licenca` AS `licenca`, `e`.`dataInicioCredenciamento` AS `dataInicioCredenciamento`, `e`.`dataFimCredenciamento` AS `dataFimCredenciamento`, `e`.`status` AS `statusEvento`, `e`.`dataCadastro` AS `dataCadastroEvento`, `e`.`dataEdicao` AS `dataEdicaoEvento`, `e`.`aprovado` AS `aprovado`, `c`.`id` AS `idCategoria`, `c`.`nomeCategoria` AS `nomeCategoria`, `l`.`nomeOficial` AS `nomeLocal`, `l`.`id` AS `idLocal` FROM ((`tblEvento` `e` join `tblEventoCategoria` `c` on((`e`.`idCategoria` = `c`.`id`))) left join `tblEventoLocal` `l` on((`e`.`idLocal` = `l`.`id`))) ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vwLogin`
--
DROP TABLE IF EXISTS `vwLogin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`crede`@`%` SQL SECURITY DEFINER VIEW `vwLogin`  AS SELECT `p`.`id` AS `id`, `u`.`id` AS `idUsuario`, `u`.`usuario` AS `usuario`, `p`.`nome` AS `nome`, `p`.`sobrenome` AS `sobrenome`, `p`.`apelido` AS `apelido`, `e`.`email` AS `email`, `u`.`senha` AS `senha`, `u`.`sessionID` AS `sessionID`, `u`.`status` AS `STATUS`, `doc`.`documento` AS `cpf`, `tel`.`telefone` AS `telefone`, (case when (`perm`.`status` = 'T') then `perm`.`id` end) AS `idPermissao`, (case when (`perm`.`status` = 'T') then `perm`.`permissao` end) AS `permissao` FROM (((((`tblEmail` `e` join `tblPessoa` `p` on((`e`.`idPessoa` = `p`.`id`))) join `tblUsuario` `u` on((`u`.`idPessoa` = `p`.`id`))) left join `tblPermissao` `perm` on((`u`.`idPermissao` = `perm`.`id`))) left join (select `tblDocumento`.`idPessoa` AS `idPessoa`,`tblDocumento`.`documento` AS `documento` from `tblDocumento` where (`tblDocumento`.`tipo` = 'CPF')) `doc` on((`doc`.`idPessoa` = `p`.`id`))) left join (select `tblTelefone`.`idPessoa` AS `idPessoa`,`tblTelefone`.`telefone` AS `telefone` from `tblTelefone` where (`tblTelefone`.`tipo` = 'Celular')) `tel` on((`tel`.`idPessoa` = `p`.`id`))) ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vwUsuarios`
--
DROP TABLE IF EXISTS `vwUsuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`crede`@`%` SQL SECURITY DEFINER VIEW `vwUsuarios`  AS SELECT `p`.`id` AS `idPessoa`, `u`.`id` AS `idUsuario`, `u`.`usuario` AS `usuario`, `p`.`nome` AS `nome`, `p`.`sobrenome` AS `sobrenome`, `p`.`apelido` AS `apelido`, `e`.`email` AS `email`, `u`.`senha` AS `senha`, `u`.`sessionID` AS `sessionID`, `u`.`token` AS `token`, `u`.`status` AS `status`, `perm`.`id` AS `idPermissao`, `perm`.`permissao` AS `permissao`, `u`.`dataCriacao` AS `dataCriacao`, `u`.`dataEdicao` AS `dataEdicao` FROM (((((`tblPessoa` `p` left join `tblEmail` `e` on((`e`.`idPessoa` = `p`.`id`))) join `tblUsuario` `u` on((`u`.`idPessoa` = `p`.`id`))) join `tblPermissao` `perm` on((`u`.`idPermissao` = `perm`.`id`))) left join (select `tblDocumento`.`idPessoa` AS `idPessoa`,`tblDocumento`.`documento` AS `documento` from `tblDocumento` where (`tblDocumento`.`tipo` = 'CPF')) `doc` on((`doc`.`idPessoa` = `p`.`id`))) left join (select `tblTelefone`.`idPessoa` AS `idPessoa`,`tblTelefone`.`telefone` AS `telefone` from `tblTelefone` where (`tblTelefone`.`tipo` = 'Celular')) `tel` on((`tel`.`idPessoa` = `p`.`id`))) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tblArquivo`
--
ALTER TABLE `tblArquivo`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblCredencial`
--
ALTER TABLE `tblCredencial`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblCredencialPeriodo`
--
ALTER TABLE `tblCredencialPeriodo`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblDocumento`
--
ALTER TABLE `tblDocumento`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id_UNIQUE` (`id`) USING BTREE,
  ADD UNIQUE KEY `documento` (`documento`) USING BTREE,
  ADD KEY `fk_tblDocumento_tblPessoa1_idx` (`idPessoa`) USING BTREE;

--
-- Índices para tabela `tblEmail`
--
ALTER TABLE `tblEmail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Índices para tabela `tblEndereco`
--
ALTER TABLE `tblEndereco`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_tblEndereco_tblPessoa1_idx` (`idPessoa`);

--
-- Índices para tabela `tblEntradas`
--
ALTER TABLE `tblEntradas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblEvento`
--
ALTER TABLE `tblEvento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblEventoCategoria`
--
ALTER TABLE `tblEventoCategoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblEventoComponentes`
--
ALTER TABLE `tblEventoComponentes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblEventoLocal`
--
ALTER TABLE `tblEventoLocal`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblHistorico`
--
ALTER TABLE `tblHistorico`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblHistoricoToken`
--
ALTER TABLE `tblHistoricoToken`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblLeitor`
--
ALTER TABLE `tblLeitor`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblLeitorFoto`
--
ALTER TABLE `tblLeitorFoto`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblLog`
--
ALTER TABLE `tblLog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_tblLog_tblUsuario1_idx` (`idUsuario`);

--
-- Índices para tabela `tblLote`
--
ALTER TABLE `tblLote`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblLotePeriodo`
--
ALTER TABLE `tblLotePeriodo`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblPermissao`
--
ALTER TABLE `tblPermissao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idtblPermissao_UNIQUE` (`id`);

--
-- Índices para tabela `tblPessoa`
--
ALTER TABLE `tblPessoa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Índices para tabela `tblRelCredencialSetor`
--
ALTER TABLE `tblRelCredencialSetor`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblRelLoteSetor`
--
ALTER TABLE `tblRelLoteSetor`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblSetor`
--
ALTER TABLE `tblSetor`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblTelefone`
--
ALTER TABLE `tblTelefone`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_tblTelefone_tblPessoa_idx` (`idPessoa`);

--
-- Índices para tabela `tblTerminal`
--
ALTER TABLE `tblTerminal`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tblToken`
--
ALTER TABLE `tblToken`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Índices para tabela `tblUsuario`
--
ALTER TABLE `tblUsuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `idPessoa` (`idPessoa`),
  ADD KEY `fk_tblUsuario_tblPessoa1_idx` (`idPessoa`),
  ADD KEY `fk_tblUsuario_tblPermissao1_idx` (`idPermissao`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tblArquivo`
--
ALTER TABLE `tblArquivo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblCredencial`
--
ALTER TABLE `tblCredencial`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblCredencialPeriodo`
--
ALTER TABLE `tblCredencialPeriodo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblDocumento`
--
ALTER TABLE `tblDocumento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblEmail`
--
ALTER TABLE `tblEmail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tblEndereco`
--
ALTER TABLE `tblEndereco`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblEntradas`
--
ALTER TABLE `tblEntradas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblEvento`
--
ALTER TABLE `tblEvento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tblEventoCategoria`
--
ALTER TABLE `tblEventoCategoria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tblEventoComponentes`
--
ALTER TABLE `tblEventoComponentes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tblEventoLocal`
--
ALTER TABLE `tblEventoLocal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tblHistorico`
--
ALTER TABLE `tblHistorico`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblHistoricoToken`
--
ALTER TABLE `tblHistoricoToken`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblLeitor`
--
ALTER TABLE `tblLeitor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tblLeitorFoto`
--
ALTER TABLE `tblLeitorFoto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblLog`
--
ALTER TABLE `tblLog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblLote`
--
ALTER TABLE `tblLote`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tblLotePeriodo`
--
ALTER TABLE `tblLotePeriodo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tblPermissao`
--
ALTER TABLE `tblPermissao`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tblPessoa`
--
ALTER TABLE `tblPessoa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tblRelCredencialSetor`
--
ALTER TABLE `tblRelCredencialSetor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblRelLoteSetor`
--
ALTER TABLE `tblRelLoteSetor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tblSetor`
--
ALTER TABLE `tblSetor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tblTelefone`
--
ALTER TABLE `tblTelefone`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblTerminal`
--
ALTER TABLE `tblTerminal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tblToken`
--
ALTER TABLE `tblToken`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tblUsuario`
--
ALTER TABLE `tblUsuario`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
