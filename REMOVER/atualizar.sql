alter table tamanhos drop column `numero`;
alter table tamanhos add column `numero` varchar(10);

alter table produtos_imagens drop idcor;
alter table produtos_imagens add idcor int(11);

alter table produtos add column `comprimento` int(11) NOT NULL DEFAULT 16;
alter table produtos add column `altura` int(11) NOT NULL DEFAULT 2;
alter table produtos add column `largura` int(11) NOT NULL DEFAULT 11;
ALTER TABLE `produtos` ADD `ref` VARCHAR(20) NOT NULL AFTER `largura`;

alter table produtos_estoque add column `valor` decimal(10,2) NOT NULL DEFAULT 0;

alter table config_site add column `whatsapp` varchar(150) DEFAULT NULL;

alter table config_frete drop column `peso`;
alter table config_frete drop column `comprimento`;
alter table config_frete drop column `altura`;
alter table config_frete drop column `largura`;

alter table config_frete add column `empresa` varchar(10) DEFAULT NULL;
alter table config_frete add column `senha` varchar(10) DEFAULT NULL;
alter table config_frete add column `PAC` varchar(5) DEFAULT NULL;
alter table config_frete add column `SEDEX` varchar(5) DEFAULT NULL;

ALTER TABLE `produtos` ADD `ref` VARCHAR(20) NOT NULL AFTER `largura`;

ALTER TABLE `produtos` ADD `tempProd` INT NOT NULL AFTER `ref`;
ALTER TABLE `produtos` CHANGE `tempProd` `tempProd` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `produtos_imagens` ADD `ordem` INT NULL AFTER `status`;
