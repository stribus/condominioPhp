-- condominio.cliente definition

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nome` varchar(150) DEFAULT NULL,
  `endereco` varchar(256) DEFAULT NULL,
  `contato` varchar(256) DEFAULT NULL,
  `obs` longblob DEFAULT NULL,
  `permitir_saldo_negativo` tinyint(1) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_cliente`),
  KEY `cliente_codigo_IDX` (`codigo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- condominio.mesa definition

CREATE TABLE `mesa` (
  `id_mesa` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_mesa`),
  UNIQUE KEY `mesa_un` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- condominio.temporadas definition

CREATE TABLE `temporadas` (
  `id_temporada` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_temporada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- condominio.tipo_pagamento definition

CREATE TABLE `tipo_pagamento` (
  `id_tipo_pgmto` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `visivel` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_tipo_pgmto`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;


-- condominio.usuario definition

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `senha` varchar(512) NOT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_un` (`usuario`),
  KEY `usuario_usuario_IDX` (`usuario`,`senha`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- condominio.dependentes definition

CREATE TABLE `dependentes` (
  `id_dependente` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `fk_cliente` int(11) NOT NULL,
  `fone` varchar(150) DEFAULT NULL,
  `obs` varchar(150) DEFAULT NULL,
  `permitir_retirar` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_dependente`),
  KEY `dependentes_FK` (`fk_cliente`),
  CONSTRAINT `dependentes_FK` FOREIGN KEY (`fk_cliente`) REFERENCES `cliente` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- condominio.pedido definition

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `fk_temporada` int(11) NOT NULL,
  `fk_mesa` int(11) DEFAULT NULL,
  `dthr_abertura` datetime NOT NULL DEFAULT current_timestamp(),
  `dthr_fechamento` datetime DEFAULT NULL,
  `fk_dependente` int(11) DEFAULT NULL,
  `pago` tinyint(1) NOT NULL DEFAULT 0,
  `anotar` tinyint(1) NOT NULL DEFAULT 0,
  `fk_cliente` int(11) DEFAULT NULL,
  `nome_dependente` varchar(255) DEFAULT NULL,
  `fk_tp_pagamento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `pedido_FK` (`fk_cliente`),
  KEY `pedido_FK_1` (`fk_dependente`),
  KEY `pedido_FK_2` (`fk_mesa`),
  KEY `pedido_FK_3` (`fk_temporada`),
  CONSTRAINT `pedido_FK` FOREIGN KEY (`fk_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `pedido_FK_1` FOREIGN KEY (`fk_dependente`) REFERENCES `dependentes` (`id_dependente`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `pedido_FK_2` FOREIGN KEY (`fk_mesa`) REFERENCES `mesa` (`id_mesa`),
  CONSTRAINT `pedido_FK_3` FOREIGN KEY (`fk_temporada`) REFERENCES `temporadas` (`id_temporada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- condominio.produtos definition

CREATE TABLE `produtos` (
  `id_produtos` bigint(20) NOT NULL AUTO_INCREMENT,
  `fk_temporada` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `valor_uni` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id_produtos`),
  UNIQUE KEY `produtos_un` (`fk_temporada`,`codigo`),
  CONSTRAINT `produtos_FK` FOREIGN KEY (`fk_temporada`) REFERENCES `temporadas` (`id_temporada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- condominio.movimentacoes definition

CREATE TABLE `movimentacoes` (
  `id_mov` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` int(1) NOT NULL COMMENT '1-produto,2-pagamento',
  `fk_produto` bigint(20) DEFAULT NULL,
  `fk_pagamento` int(11) DEFAULT NULL,
  `quantidade` decimal(15,2) NOT NULL DEFAULT 1.00,
  `valor` decimal(15,2) NOT NULL,
  `fk_pedido` int(11) DEFAULT NULL,
  `fk_cliente_anotado` int(11) DEFAULT NULL,
  `dthr_lançamento` timestamp NOT NULL DEFAULT current_timestamp(),
  `excluido` tinyint(1) NOT NULL DEFAULT 0,
  `usuario_exclusao` varchar(255) DEFAULT NULL,
  `fk_dependente` int(11) DEFAULT NULL,
  `fk_temporada` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `pago` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_mov`),
  KEY `movimentacoes_FK` (`fk_cliente_anotado`),
  KEY `movimentacoes_FK_1` (`fk_dependente`),
  KEY `movimentacoes_FK_2` (`fk_temporada`),
  KEY `movimentacoes_FK_4` (`fk_pagamento`),
  KEY `movimentacoes_FK_3` (`fk_produto`),
  KEY `movimentacoes_FK_5` (`fk_pedido`),
  CONSTRAINT `movimentacoes_FK` FOREIGN KEY (`fk_cliente_anotado`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `movimentacoes_FK_1` FOREIGN KEY (`fk_dependente`) REFERENCES `dependentes` (`id_dependente`),
  CONSTRAINT `movimentacoes_FK_2` FOREIGN KEY (`fk_temporada`) REFERENCES `temporadas` (`id_temporada`),
  CONSTRAINT `movimentacoes_FK_3` FOREIGN KEY (`fk_produto`) REFERENCES `produtos` (`id_produtos`),
  CONSTRAINT `movimentacoes_FK_4` FOREIGN KEY (`fk_pagamento`) REFERENCES `tipo_pagamento` (`id_tipo_pgmto`),
  CONSTRAINT `movimentacoes_FK_5` FOREIGN KEY (`fk_pedido`) REFERENCES `pedido` (`id_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;