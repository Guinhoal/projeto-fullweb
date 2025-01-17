-- MySQL Workbench Synchronization
-- Generated: 2024-11-22 19:35
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Jesus

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `a2023952616@teiacoltec.org` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`associado` (
  `idassociado` INT(11) NOT NULL AUTO_INCREMENT,
  `cpf` VARCHAR(45) NOT NULL,
  `cargo` VARCHAR(45) NOT NULL,
  `nome` VARCHAR(80) NOT NULL,
  `departamento_iddepartamento` INT(11) NOT NULL,
  PRIMARY KEY (`idassociado`),
  UNIQUE INDEX `idassociado_UNIQUE` (`idassociado` ASC) VISIBLE,
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC) VISIBLE,
  INDEX `fk_associado_departamento_idx` (`departamento_iddepartamento` ASC) VISIBLE,
  CONSTRAINT `fk_associado_departamento`
    FOREIGN KEY (`departamento_iddepartamento`)
    REFERENCES `a2023952616@teiacoltec.org`.`departamento` (`iddepartamento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`departamento` (
  `iddepartamento` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(65) NULL DEFAULT NULL,
  `idcoordenador` INT(11) NOT NULL,
  PRIMARY KEY (`iddepartamento`),
  UNIQUE INDEX `iddepartamento_UNIQUE` (`iddepartamento` ASC) VISIBLE,
  UNIQUE INDEX `idcoordenador_UNIQUE` (`idcoordenador` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`associado_contato` (
  `idassociado_contato` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(75) NOT NULL,
  `telefone` VARCHAR(45) NOT NULL,
  `associado_idassociado` INT(11) NOT NULL,
  PRIMARY KEY (`idassociado_contato`),
  UNIQUE INDEX `idassociado_contato_UNIQUE` (`idassociado_contato` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  UNIQUE INDEX `telefone_UNIQUE` (`telefone` ASC) VISIBLE,
  INDEX `fk_associado_contato_associado1_idx` (`associado_idassociado` ASC) VISIBLE,
  CONSTRAINT `fk_associado_contato_associado1`
    FOREIGN KEY (`associado_idassociado`)
    REFERENCES `a2023952616@teiacoltec.org`.`associado` (`idassociado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`endereco_associado` (
  `idendereco_associado` INT(11) NOT NULL AUTO_INCREMENT,
  `pais` VARCHAR(45) NOT NULL,
  `estado` VARCHAR(55) NOT NULL,
  `cidade` VARCHAR(65) NOT NULL,
  `endereco` VARCHAR(95) NOT NULL,
  `associado_idassociado` INT(11) NOT NULL,
  PRIMARY KEY (`idendereco_associado`),
  UNIQUE INDEX `idendereco_associado_UNIQUE` (`idendereco_associado` ASC) VISIBLE,
  INDEX `fk_endereco_associado_associado1_idx` (`associado_idassociado` ASC) VISIBLE,
  CONSTRAINT `fk_endereco_associado_associado1`
    FOREIGN KEY (`associado_idassociado`)
    REFERENCES `a2023952616@teiacoltec.org`.`associado` (`idassociado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`usuario` (
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(75) NOT NULL,
  `cpf` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE INDEX `idusuario_UNIQUE` (`idusuario` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`endereco_usuario` (
  `idendereco_usuario` INT(11) NOT NULL AUTO_INCREMENT,
  `pais` VARCHAR(65) NOT NULL,
  `estado` VARCHAR(65) NOT NULL,
  `cidade` VARCHAR(65) NOT NULL,
  `endereco` VARCHAR(95) NOT NULL,
  `usuario_idusuario` INT(11) NOT NULL,
  PRIMARY KEY (`idendereco_usuario`),
  INDEX `fk_endereco_usuario_usuario1_idx` (`usuario_idusuario` ASC) VISIBLE,
  CONSTRAINT `fk_endereco_usuario_usuario1`
    FOREIGN KEY (`usuario_idusuario`)
    REFERENCES `a2023952616@teiacoltec.org`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`usuario_contato` (
  `idusuario_contato` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(85) NOT NULL,
  `telefone` VARCHAR(45) NOT NULL,
  `usuario_idusuario` INT(11) NOT NULL,
  PRIMARY KEY (`idusuario_contato`),
  UNIQUE INDEX `idusuario_contato_UNIQUE` (`idusuario_contato` ASC) VISIBLE,
  INDEX `fk_usuario_contato_usuario1_idx` (`usuario_idusuario` ASC) VISIBLE,
  CONSTRAINT `fk_usuario_contato_usuario1`
    FOREIGN KEY (`usuario_idusuario`)
    REFERENCES `a2023952616@teiacoltec.org`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`produto` (
  `idproduto` INT(11) NOT NULL,
  `data_lancamento` DATE NOT NULL,
  `descricao` VARCHAR(115) NOT NULL,
  `editora` VARCHAR(45) NOT NULL,
  `autor` VARCHAR(65) NOT NULL,
  `nome` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`idproduto`),
  UNIQUE INDEX `idproduto_UNIQUE` (`idproduto` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`estoque` (
  `quantidade` INT(11) NOT NULL,
  `idestoque` INT(11) NOT NULL,
  `produto_idproduto` INT(11) NOT NULL,
  PRIMARY KEY (`idestoque`),
  UNIQUE INDEX `idestoque_UNIQUE` (`idestoque` ASC) VISIBLE,
  INDEX `fk_estoque_produto1_idx` (`produto_idproduto` ASC) VISIBLE,
  CONSTRAINT `fk_estoque_produto1`
    FOREIGN KEY (`produto_idproduto`)
    REFERENCES `a2023952616@teiacoltec.org`.`produto` (`idproduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`categoria` (
  `idcategoria` INT(11) NOT NULL,
  `nome` VARCHAR(65) NOT NULL,
  PRIMARY KEY (`idcategoria`),
  UNIQUE INDEX `idcategoria_UNIQUE` (`idcategoria` ASC) VISIBLE,
  UNIQUE INDEX `nome_UNIQUE` (`nome` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`produto_categoria` (
  `produto_idproduto` INT(11) NOT NULL,
  `categoria_idcategoria` INT(11) NOT NULL,
  PRIMARY KEY (`produto_idproduto`, `categoria_idcategoria`),
  INDEX `fk_produto_has_categoria_categoria1_idx` (`categoria_idcategoria` ASC) VISIBLE,
  INDEX `fk_produto_has_categoria_produto1_idx` (`produto_idproduto` ASC) VISIBLE,
  CONSTRAINT `fk_produto_has_categoria_produto1`
    FOREIGN KEY (`produto_idproduto`)
    REFERENCES `a2023952616@teiacoltec.org`.`produto` (`idproduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produto_has_categoria_categoria1`
    FOREIGN KEY (`categoria_idcategoria`)
    REFERENCES `a2023952616@teiacoltec.org`.`categoria` (`idcategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`venda` (
  `idvenda` INT(11) NOT NULL,
  `preco` FLOAT(11) NOT NULL,
  `status` VARCHAR(55) NOT NULL,
  `data` DATE NOT NULL,
  `produto_idproduto` INT(11) NOT NULL,
  `usuario_idusuario` INT(11) NOT NULL,
  PRIMARY KEY (`idvenda`, `usuario_idusuario`),
  UNIQUE INDEX `idvenda_UNIQUE` (`idvenda` ASC) VISIBLE,
  INDEX `fk_venda_produto1_idx` (`produto_idproduto` ASC) VISIBLE,
  INDEX `fk_venda_usuario1_idx` (`usuario_idusuario` ASC) VISIBLE,
  CONSTRAINT `fk_venda_produto1`
    FOREIGN KEY (`produto_idproduto`)
    REFERENCES `a2023952616@teiacoltec.org`.`produto` (`idproduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_venda_usuario1`
    FOREIGN KEY (`usuario_idusuario`)
    REFERENCES `a2023952616@teiacoltec.org`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


//Trocas:

-- Adicionando coluna 'imagem' na tabela 'autor'
ALTER TABLE `autor` 
ADD COLUMN `imagem` VARCHAR(255) NOT NULL;

-- Adicionando coluna 'imagem' na tabela 'editora'
ALTER TABLE `editora` 
ADD COLUMN `imagem` VARCHAR(255) NOT


-- Criando a tabela de Editoras
CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`editora` (
  `ideditora` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ideditora`),
  UNIQUE INDEX `ideditora_UNIQUE` (`ideditora` ASC) VISIBLE,
  UNIQUE INDEX `nome_UNIQUE` (`nome` ASC) VISIBLE
) ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8;

-- Criando a tabela de Autores
CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`autor` (
  `idautor` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`idautor`),
  UNIQUE INDEX `idautor_UNIQUE` (`idautor` ASC) VISIBLE,
  UNIQUE INDEX `nome_UNIQUE` (`nome` ASC) VISIBLE
) ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8;

-- Alterando a tabela Produto para referenciar Editoras e Autores
ALTER TABLE `a2023952616@teiacoltec.org`.`produto`
  DROP COLUMN `editora`,
  DROP COLUMN `autor`,
  ADD COLUMN `ideditora` INT(11) NOT NULL,
  ADD COLUMN `idautor` INT(11) NOT NULL,
  ADD CONSTRAINT `fk_produto_editora`
    FOREIGN KEY (`ideditora`)
    REFERENCES `a2023952616@teiacoltec.org`.`editora` (`ideditora`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_autor`
    FOREIGN KEY (`idautor`)
    REFERENCES `a2023952616@teiacoltec.org`.`autor` (`idautor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION;

ALTER TABLE `produto`
ADD COLUMN `preco` DECIMAL(10,2) NOT NULL;


ALTER TABLE `a2023952616@teiacoltec.org`.`associado`
ADD COLUMN `senha` VARCHAR(255) NOT NULL;

CREATE TABLE carrinho (
    idcarrinho INT AUTO_INCREMENT PRIMARY KEY,
    usuario_idusuario INT NOT NULL,
    FOREIGN KEY (usuario_idusuario) REFERENCES usuario(idusuario)
);


CREATE TABLE carrinho_produto (
    idcarrinho_produto INT AUTO_INCREMENT PRIMARY KEY,
    carrinho_idcarrinho INT NOT NULL,
    produto_idproduto INT NOT NULL,
    quantidade INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL, -- Preço do produto no momento da adição ao carrinho
    FOREIGN KEY (carrinho_idcarrinho) REFERENCES carrinho(idcarrinho),
    FOREIGN KEY (produto_idproduto) REFERENCES produto(idproduto)
);

ALTER TABLE venda
ADD COLUMN forma_pagamento VARCHAR(50) NOT NULL; -- Exemplo: 'Cartão de Crédito', 'Boleto', 'Pix'


-- Criar a tabela associativa venda_produto
CREATE TABLE IF NOT EXISTS venda_produto (
    idvenda_produto INT AUTO_INCREMENT PRIMARY KEY,
    venda_idvenda INT NOT NULL,
    produto_idproduto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL, -- Preço do produto no momento da venda
    FOREIGN KEY (venda_idvenda) REFERENCES venda(idvenda) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (produto_idproduto) REFERENCES produto(idproduto) ON DELETE NO ACTION ON UPDATE CASCADE
);

-- Remover a coluna produto_idproduto da tabela venda
ALTER TABLE venda
DROP COLUMN produto_idproduto;

ALTER TABLE `usuario`
ADD COLUMN `is_associado` BOOLEAN NOT NULL DEFAULT 0;


CREATE TABLE IF NOT EXISTS `a2023952616@teiacoltec.org`.`creditos` (
  `idusuario` INT(11) NOT NULL,
  `creditos` BIGINT NOT NULL,
  PRIMARY KEY (`idusuario`),
  CONSTRAINT `fk_creditos_usuario`
    FOREIGN KEY (`idusuario`)
    REFERENCES `a2023952616@teiacoltec.org`.`usuario` (`idusuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE venda 
CHANGE COLUMN `status` `estado` VARCHAR(80) NOT NULL;

ALTER TABLE venda 
CHANGE COLUMN `data` `data_venda` DATE NOT NULL;

ALTER TABLE venda MODIFY COLUMN data_venda DATETIME NULL;

-- Passo 1: Remover a restrição de chave estrangeira da tabela venda_produto
ALTER TABLE venda_produto DROP FOREIGN KEY venda_produto_ibfk_1;

-- Passo 2: Remover a chave primária atual
ALTER TABLE venda DROP PRIMARY KEY;

-- Passo 3: Modificar a coluna 'idvenda' para AUTO_INCREMENT
ALTER TABLE venda MODIFY COLUMN idvenda INT(11) NOT NULL AUTO_INCREMENT;

-- Passo 4: Definir 'idvenda' como a nova chave primária
ALTER TABLE venda ADD PRIMARY KEY (idvenda);

-- Passo 5: Adicionar novamente a restrição de chave estrangeira na tabela venda_produto
ALTER TABLE venda_produto ADD CONSTRAINT venda_produto_ibfk_1 FOREIGN KEY (venda_idvenda) REFERENCES venda(idvenda) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE venda ADD COLUMN endereco_id INT(11) NOT NULL;