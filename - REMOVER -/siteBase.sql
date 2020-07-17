-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 13-Abr-2020 às 16:50
-- Versão do servidor: 10.2.31-MariaDB-log
-- versão do PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `eletrotonon_data`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `acessos`
--

CREATE TABLE `acessos` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idusuario` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `banners`
--

CREATE TABLE `banners` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `imagem` varchar(150) NOT NULL,
  `ordem` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog`
--

CREATE TABLE `blog` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `texto` text NOT NULL,
  `imagem` varchar(150) DEFAULT NULL,
  `data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog_categorias`
--

CREATE TABLE `blog_categorias` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastros`
--

CREATE TABLE `cadastros` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sobrenome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `telefone_02` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `senha` varchar(150) NOT NULL,
  `data_cadastro` date NOT NULL,
  `cpf` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastros_enderecos`
--

CREATE TABLE `cadastros_enderecos` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idcadastro` int(11) NOT NULL,
  `endereco` varchar(250) NOT NULL,
  `numero` int(11) NOT NULL,
  `complemento` varchar(250) DEFAULT NULL,
  `referencia` varchar(150) DEFAULT NULL,
  `bairro` varchar(150) NOT NULL,
  `cidade` varchar(150) NOT NULL,
  `estado` char(2) NOT NULL,
  `cep` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `campanha`
--

CREATE TABLE `campanha` (
  `id` int(11) NOT NULL,
  `descricao` varchar(80) DEFAULT NULL,
  `produto` float DEFAULT NULL,
  `categoria` float DEFAULT NULL,
  `subCategoria` float DEFAULT NULL,
  `desconto` decimal(10,2) DEFAULT NULL,
  `ativa` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idcarrinho` varchar(50) NOT NULL,
  `idcadastro` int(11) NOT NULL,
  `idproduto` int(11) NOT NULL,
  `idtamanho` int(11) NOT NULL,
  `idcor` int(11) NOT NULL,
  `valor` double NOT NULL,
  `qtde` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `como_comprar`
--

CREATE TABLE `como_comprar` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `config_frete`
--

CREATE TABLE `config_frete` (
  `cep_origem` char(8) NOT NULL,
  
  `empresa` varchar(10) DEFAULT NULL,
  `senha` varchar(10) DEFAULT NULL,
  `PAC` varchar(5) DEFAULT NULL,
  `SEDEX` varchar(5) DEFAULT NULL,

  -- `peso` double NOT NULL,
  -- `comprimento` double NOT NULL,
  -- `altura` double NOT NULL,
  -- `largura` double NOT NULL,

  `mao_propria` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `config_site`
--

CREATE TABLE `config_site` (
  `facebook` varchar(150) DEFAULT NULL,
  `instagram` varchar(150) DEFAULT NULL,
  `twitter` varchar(150) DEFAULT NULL,
  `pinterest` varchar(150) DEFAULT NULL,
  `google` varchar(150) DEFAULT NULL,
  `whatsapp` varchar(150) DEFAULT NULL,
  `logo` varchar(150) DEFAULT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(50) NOT NULL,
  `endereco` text NOT NULL,
  `url_maps` text DEFAULT NULL,
  `funcionamento` varchar(50) NOT NULL,
  `frete` varchar(50) NOT NULL,
  `devolucao` varchar(50) NOT NULL,
  `pagamento` int(1) NOT NULL DEFAULT 1 COMMENT '1 = módulo de pagto, 2 = presencial, 3 = ambos',
  `pac` int(1) NOT NULL DEFAULT 1 COMMENT '1 = gratuito, 2 = cobrado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cores`
--

CREATE TABLE `cores` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `titulo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa`
--

CREATE TABLE `empresa` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `faq`
--

CREATE TABLE `faq` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `pergunta` varchar(250) NOT NULL,
  `resposta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `frete`
--

CREATE TABLE `frete` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idpedido` varchar(50) NOT NULL,
  `cep_destino` char(10) DEFAULT NULL,
  `servico` varchar(50) DEFAULT NULL,
  `valor` double NOT NULL,
  `prazo` int(2) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logCompra`
--

CREATE TABLE `logCompra` (
  `id` int(11) NOT NULL,
  `sellerid` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `amount` varchar(40) NOT NULL,
  `customerid` varchar(40) NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `firstname` varchar(120) NOT NULL,
  `lastname` varchar(120) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `email` varchar(120) NOT NULL,
  `rua` varchar(100) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `neighborhood` varchar(60) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `itens` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcas`
--

CREATE TABLE `marcas` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `titulo` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `newsletter`
--

CREATE TABLE `newsletter` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idcadastro` int(11) NOT NULL,
  `idcarrinho` varchar(50) NOT NULL,
  `valor` double NOT NULL,
  `pagamento` int(1) NOT NULL DEFAULT 1 COMMENT '1 = pelo site, 2 = presencial',
  `data_geracao` date NOT NULL,
  `data_baixa` date DEFAULT NULL,
  `data_cancelamento` date DEFAULT NULL,
  `data_envio` date DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0 = Pendente, 1 = Confirmado, 2 = Enviado, 3 = Cancelado',
  `entrega` varchar(20) NOT NULL DEFAULT '' COMMENT '1: sedex; 2: retirar na loja;',
  `codRastreio` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos_detalhe`
--

CREATE TABLE `pedidos_detalhe` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idpedido` int(11) NOT NULL,
  `idproduto` int(11) NOT NULL,
  `idtamanho` int(11) NOT NULL,
  `idcor` int(11) NOT NULL,
  `descricao` varchar(250) NOT NULL,
  `valor` double NOT NULL,
  `qtde` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos_enderecos`
--

CREATE TABLE `pedidos_enderecos` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idpedido` int(11) NOT NULL,
  `idcadastro` int(11) NOT NULL,
  `endereco` varchar(250) NOT NULL,
  `numero` int(11) NOT NULL,
  `complemento` varchar(250) DEFAULT NULL,
  `referencia` varchar(150) DEFAULT NULL,
  `bairro` varchar(150) NOT NULL,
  `cidade` varchar(150) NOT NULL,
  `estado` char(2) NOT NULL,
  `cep` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `politica_troca`
--

CREATE TABLE `politica_troca` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `idsubcategoria` int(11) DEFAULT NULL,
  `idmarca` int(11) DEFAULT NULL,
  `nome` varchar(150) NOT NULL,
  `valor_produto` double NOT NULL,
  `valor_desconto` double DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `tags` varchar(250) NOT NULL,
  `descricao` text NOT NULL,
  `informacoes` text DEFAULT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `visualizacoes` int(11) NOT NULL DEFAULT 0,
  `destaque` int(1) NOT NULL DEFAULT 2,
  `ind_cores` int(1) NOT NULL DEFAULT 1,
  `status` int(1) NOT NULL DEFAULT 1,
  `comprimento` int(11) NOT NULL DEFAULT 16,
  `altura` int(11) NOT NULL DEFAULT 2,
  `largura` int(11) NOT NULL DEFAULT 11,
  `ref` VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_categorias`
--

CREATE TABLE `produtos_categorias` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_estoque`
--

CREATE TABLE `produtos_estoque` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idproduto` int(11) NOT NULL,
  `idcor` int(11) NOT NULL,
  `idtamanho` int(11) NOT NULL,
  `estoque` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_imagens`
--

CREATE TABLE `produtos_imagens` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idproduto` int(11) NOT NULL,
  `idcor` int(11),
  `imagem` varchar(150) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_subcategorias`
--

CREATE TABLE `produtos_subcategorias` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `publicidade`
--

CREATE TABLE `publicidade` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `url` varchar(150) NOT NULL,
  `imagem` varchar(150) NOT NULL,
  `tipo` int(1) NOT NULL DEFAULT 1,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tamanhos`
--

CREATE TABLE `tamanhos` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `numero` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `termos_uso`
--

CREATE TABLE `termos_uso` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `testemunhos`
--

CREATE TABLE `testemunhos` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `mensagem` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `login` varchar(10) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `status` int(1) NOT NULL COMMENT '1 = Ativo, 2 = Inativo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`codigo`, `nome`, `login`, `senha`, `status`) VALUES
(1, 'Administrador', 'admin', 'e99a18c428cb38d5f260853678922e03', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `video`
--

CREATE TABLE `video` (
  `codigo` int(11) UNSIGNED NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `acessos`
--
ALTER TABLE `acessos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Índices para tabela `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idcategoria` (`idcategoria`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Índices para tabela `blog_categorias`
--
ALTER TABLE `blog_categorias`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `cadastros`
--
ALTER TABLE `cadastros`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `cadastros_enderecos`
--
ALTER TABLE `cadastros_enderecos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idcadastro` (`idcadastro`);

--
-- Índices para tabela `campanha`
--
ALTER TABLE `campanha`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idcadastro` (`idcadastro`,`idproduto`),
  ADD KEY `idtamanho` (`idtamanho`),
  ADD KEY `idcor` (`idcor`);

--
-- Índices para tabela `como_comprar`
--
ALTER TABLE `como_comprar`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `cores`
--
ALTER TABLE `cores`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `frete`
--
ALTER TABLE `frete`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idfatura` (`idpedido`);

--
-- Índices para tabela `logCompra`
--
ALTER TABLE `logCompra`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idcadastro` (`idcadastro`),
  ADD KEY `idcarrinho` (`idcarrinho`);

--
-- Índices para tabela `pedidos_detalhe`
--
ALTER TABLE `pedidos_detalhe`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idfatura` (`idpedido`,`idproduto`),
  ADD KEY `idtamanho` (`idtamanho`,`idcor`);

--
-- Índices para tabela `pedidos_enderecos`
--
ALTER TABLE `pedidos_enderecos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idcadastro` (`idcadastro`),
  ADD KEY `idpedido` (`idpedido`);

--
-- Índices para tabela `politica_troca`
--
ALTER TABLE `politica_troca`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idcategoria` (`idcategoria`),
  ADD KEY `idmarca` (`idmarca`),
  ADD KEY `idsubcategoria` (`idsubcategoria`);

--
-- Índices para tabela `produtos_categorias`
--
ALTER TABLE `produtos_categorias`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `produtos_estoque`
--
ALTER TABLE `produtos_estoque`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idproduto` (`idproduto`,`idcor`,`idtamanho`);

--
-- Índices para tabela `produtos_imagens`
--
ALTER TABLE `produtos_imagens`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idproduto` (`idproduto`),
  ADD KEY `idcor` (`idcor`);

--
-- Índices para tabela `produtos_subcategorias`
--
ALTER TABLE `produtos_subcategorias`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idcategoria` (`idcategoria`);

--
-- Índices para tabela `publicidade`
--
ALTER TABLE `publicidade`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `tamanhos`
--
ALTER TABLE `tamanhos`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `termos_uso`
--
ALTER TABLE `termos_uso`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `testemunhos`
--
ALTER TABLE `testemunhos`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`codigo`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `acessos`
--
ALTER TABLE `acessos`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `banners`
--
ALTER TABLE `banners`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `blog`
--
ALTER TABLE `blog`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `blog_categorias`
--
ALTER TABLE `blog_categorias`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cadastros`
--
ALTER TABLE `cadastros`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cadastros_enderecos`
--
ALTER TABLE `cadastros_enderecos`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `campanha`
--
ALTER TABLE `campanha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `como_comprar`
--
ALTER TABLE `como_comprar`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cores`
--
ALTER TABLE `cores`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `faq`
--
ALTER TABLE `faq`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `frete`
--
ALTER TABLE `frete`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logCompra`
--
ALTER TABLE `logCompra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `marcas`
--
ALTER TABLE `marcas`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos_detalhe`
--
ALTER TABLE `pedidos_detalhe`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos_enderecos`
--
ALTER TABLE `pedidos_enderecos`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `politica_troca`
--
ALTER TABLE `politica_troca`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos_categorias`
--
ALTER TABLE `produtos_categorias`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos_estoque`
--
ALTER TABLE `produtos_estoque`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos_imagens`
--
ALTER TABLE `produtos_imagens`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos_subcategorias`
--
ALTER TABLE `produtos_subcategorias`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `publicidade`
--
ALTER TABLE `publicidade`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tamanhos`
--
ALTER TABLE `tamanhos`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `termos_uso`
--
ALTER TABLE `termos_uso`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `testemunhos`
--
ALTER TABLE `testemunhos`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `video`
--
ALTER TABLE `video`
  MODIFY `codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
