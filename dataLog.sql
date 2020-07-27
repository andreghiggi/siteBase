ALTER TABLE `carrinho` CHANGE `idtamanho` `idtamanho` INT(11) NULL;
ALTER TABLE `carrinho` CHANGE `idcor` `idcor` INT(11) NULL;
ALTER TABLE `pagseguro_configuracao` ADD `status` TINYINT(1) NOT NULL DEFAULT '1' AFTER `urlnotificacao`, ADD `sandbox` TINYINT(1) NOT NULL AFTER `status`;
ALTER TABLE `pagseguro_configuracao` CHANGE `sandbox` `sandbox` TINYINT(1) NOT NULL DEFAULT '1';
ALTER TABLE `carrinho` ADD `modelo` INT NULL AFTER `qtde`, ADD `marca` INT NULL AFTER `modelo`, ADD `nome` VARCHAR(120) NULL AFTER `marca`;
ALTER TABLE `pedidos_detalhe` ADD `modelo` INT NULL AFTER `qtde`, ADD `marca` INT NULL AFTER `modelo`, ADD `nome` VARCHAR(120) NULL AFTER `marca`;
