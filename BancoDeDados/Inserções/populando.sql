-- Inserindo Editoras
INSERT INTO `editora` (`ideditora`, `nome`, `data_criacao`, `descricao`, `imagem`) VALUES
(1, 'Nova Fronteira', '1965-01-01', 'Editora brasileira renomada por publicações literárias.', 'editora1'),
(2, 'Companhia das Letras', '1986-06-01', 'Uma das principais editoras brasileiras.', 'editora2'),
(3, 'Rocco', '1975-03-15', 'Editora conhecida pela publicação de grandes clássicos e literatura jovem.', 'editora3');

-- Inserindo Autores
INSERT INTO `autor` (`idautor`, `nome`, `data_nascimento`, `descricao`, `imagem`) VALUES
(1, 'Machado de Assis', '1839-06-21', 'Um dos maiores escritores da literatura brasileira.', 'autor1'),
(2, 'J.K. Rowling', '1965-07-31', 'Autora da famosa série Harry Potter.', 'autor2'),
(3, 'George Orwell', '1903-06-25', 'Famoso por obras distópicas como 1984 e A Revolução dos Bichos.', 'autor3');

-- Inserindo Categorias
INSERT INTO `categoria` (`idcategoria`, `nome`) VALUES
(1, 'Literatura Brasileira'),
(2, 'Fantasia'),
(3, 'Distopia');

-- Inserindo Livros (Produtos)
INSERT INTO `produto` (`idproduto`, `nome`, `data_lancamento`, `descricao`, `editora_ideditora`, `autor_idautor`, `imagem`) VALUES
(1, 'Dom Casmurro', '1899-01-01', 'Clássico da literatura brasileira.', 1, 1, 'livro1'),
(2, 'Harry Potter e a Pedra Filosofal', '1997-06-26', 'Primeiro livro da série Harry Potter.', 2, 2, 'livro2'),
(3, '1984', '1949-06-08', 'Obra-prima distópica de George Orwell.', 3, 3, 'livro3'),
(4, 'Memórias Póstumas de Brás Cubas', '1881-03-15', 'Uma narrativa irônica e inovadora de Machado de Assis.', 1, 1, 'livro4'),
(5, 'Harry Potter e a Câmara Secreta', '1998-07-02', 'Segundo livro da série Harry Potter.', 2, 2, 'livro5');

-- Inserindo Estoque
INSERT INTO `estoque` (`idestoque`, `quantidade`, `produto_idproduto`) VALUES
(1, 50, 1),
(2, 30, 2),
(3, 20, 3),
(4, 40, 4),
(5, 35, 5);

-- Relacionando Produtos às Categorias
INSERT INTO `produto_categoria` (`produto_idproduto`, `categoria_idcategoria`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 1),
(5, 2);


-- Atualizando os preços dos produtos
UPDATE `produto`
SET `preco` = 39.90
WHERE `idproduto` = 1;

UPDATE `produto`
SET `preco` = 49.90
WHERE `idproduto` = 2;

UPDATE `produto`
SET `preco` = 34.90
WHERE `idproduto` = 3;

UPDATE `produto`
SET `preco` = 29.90
WHERE `idproduto` = 4;

UPDATE `produto`
SET `preco` = 44.90
WHERE `idproduto` = 5;


-- Populando a tabela 'departamento'
INSERT INTO `a2023952616@teiacoltec.org`.`departamento` (iddepartamento, nome, idcoordenador)
VALUES 
(1, 'Gerencia', 1),
(2, 'Vendas', 2),
(3, 'Administracao', 3);

-- Populando a tabela 'associado' com coordenadores para cada departamento
INSERT INTO `a2023952616@teiacoltec.org`.`associado` (idassociado, cpf, cargo, nome, departamento_iddepartamento)
VALUES 
(1, '11111111111', 'Coordenador', 'coordenadorGerencia', 1),
(2, '22222222222', 'Coordenador', 'coordenadorVendas', 2),
(3, '33333333333', 'Coordenador', 'coordenadorAdministracao', 3);

-- Populando a tabela 'associado_contato'
INSERT INTO `a2023952616@teiacoltec.org`.`associado_contato` (idassociado_contato, email, telefone, associado_idassociado)
VALUES
(1, 'gerencia@livraria.com', '123456789', 1),
(2, 'vendas@livraria.com', '987654321', 2),
(3, 'administracao@livraria.com', '456789123', 3);

-- Populando a tabela 'endereco_associado'
INSERT INTO `a2023952616@teiacoltec.org`.`endereco_associado` (idendereco_associado, pais, estado, cidade, endereco, associado_idassociado)
VALUES
(1, 'Brasil', 'São Paulo', 'São Paulo', 'Rua A, 123', 1),
(2, 'Brasil', 'Rio de Janeiro', 'Rio de Janeiro', 'Rua B, 456', 2),
(3, 'Brasil', 'Minas Gerais', 'Belo Horizonte', 'Rua C, 789', 3);


-- Atualizando as senhas dos associados
UPDATE associado
SET senha = SHA2('senhaGerencia', 256)
WHERE idassociado = 1;

UPDATE associado
SET senha = SHA2('senhaVendas', 256)
WHERE idassociado = 2;

UPDATE associado
SET senha = SHA2('senhaAdministracao', 256)
WHERE idassociado = 3;

-- Inserindo Mais Editoras
INSERT INTO `editora` (`ideditora`, `nome`, `data_criacao`, `descricao`, `imagem`) VALUES
(4, 'Editora Record', '1940-01-01', 'Uma das maiores editoras do Brasil, com uma vasta gama de títulos.', 'editora4'),
(5, 'Editora Planeta', '1986-01-01', 'Editora de livros de ficção e não-ficção, conhecida por suas obras de sucesso.', 'editora5'),
(6, 'Grupo Editorial Novo Século', '2005-01-01', 'Editora focada em literatura jovem e infantojuvenil.', 'editora6');

-- Inserindo Mais Autores
INSERT INTO `autor` (`idautor`, `nome`, `data_nascimento`, `descricao`, `imagem`) VALUES
(4, 'Clarice Lispector', '1920-12-10', 'Uma das mais importantes escritoras brasileiras, conhecida por sua prosa introspectiva.', 'autor4'),
(5, 'J.R.R. Tolkien', '1892-09-01', 'Autor do famoso livro "O Senhor dos Anéis" e "O Hobbit".', 'autor5'),
(6, 'Agatha Christie', '1890-09-15', 'Famosa escritora de romances policiais, criadora de personagens como Hercule Poirot.', 'autor6');

-- Inserindo Mais Livros (Produtos)
INSERT INTO `produto` (`idproduto`, `nome`, `data_lancamento`, `descricao`, `ideditora`, `idautor`, `imagem`, `preco`) VALUES
(6, 'A Hora da Estrela', '1977-01-01', 'Último romance de Clarice Lispector, uma obra-prima da literatura brasileira.', 4, 4, 'livro6', 39.90),
(7, 'O Senhor dos Anéis: A Sociedade do Anel', '1954-07-29', 'Primeiro livro da trilogia épica de J.R.R. Tolkien.', 5, 5, 'livro7', 59.90),
(8, 'Assassinato no Expresso do Oriente', '1934-01-01', 'Um dos mais famosos romances policiais de Agatha Christie.', 6, 6, 'livro8', 34.90),
(9, 'O Ponto de Vista', '2000-01-01', 'Uma narrativa envolvente que explora a vida sob diferentes perspectivas.', 4, 4, 'livro9', 29.90),
(10, 'O Hobbit', '1937-09-21', 'A história que precede "O Senhor dos Anéis", uma aventura fantástica.', 5, 5, 'livro10', 49.90);

-- Inserindo Mais Estoque
INSERT INTO `estoque` (`idestoque`, `quantidade`, `produto_idproduto`) VALUES
(6, 25, 6),
(7, 15, 7),
(8, 40, 8),
(9, 20, 9),
(10, 30, 10);

-- Relacionando Novos Produtos às Categorias
INSERT INTO `produto_categoria` (`produto_idproduto`, `categoria_idcategoria`) VALUES
(6, 1), -- A Hora da Estrela -> Literatura Brasileira
(7, 2), -- O Senhor dos Anéis: A Sociedade do Anel -> Fantasia
(8, 3), -- Assassinato no Expresso do Oriente -> Distopia
(9, 1), -- O Ponto de Vista -> Literatura Brasileira
(10, 2); -- O Hobbit -> Fantasia

-- Atualizando os Preços dos Novos Produtos
UPDATE `produto`
SET `preco` = 39.90
WHERE `idproduto` = 6;

UPDATE `produto`
SET `preco` = 59.90
WHERE `idproduto` = 7;

UPDATE `produto`
SET `preco` = 34.90
WHERE `idproduto` = 8;

UPDATE `produto`
SET `preco` = 29.90
WHERE `idproduto` = 9;

UPDATE `produto`
SET `preco` = 49.90
WHERE `idproduto` = 10;

-- Inserindo Mais Editoras
INSERT INTO `editora` (`ideditora`, `nome`, `data_criacao`, `descricao`, `imagem`) VALUES
(7, 'DarkSide Books', '2012-10-31', 'Especializada em literatura de terror, fantasia e suspense.', 'editora7'),
(8, 'Intrínseca', '2003-05-01', 'Editora brasileira famosa por best-sellers internacionais.', 'editora8');

-- Inserindo Mais Autores
INSERT INTO `autor` (`idautor`, `nome`, `data_nascimento`, `descricao`, `imagem`) VALUES
(7, 'Stephen King', '1947-09-21', 'Conhecido como o mestre do terror, autor de livros como It e O Iluminado.', 'autor7'),
(8, 'Neil Gaiman', '1960-11-10', 'Autor premiado por obras de fantasia e ficção, como Sandman e Coraline.', 'autor8');

-- Inserindo Mais Categorias
INSERT INTO `categoria` (`idcategoria`, `nome`) VALUES
(4, 'Terror'),
(5, 'Suspense'),
(6, 'Ficção Fantástica');

-- Inserindo Mais Livros (Produtos)
INSERT INTO `produto` (`idproduto`, `nome`, `data_lancamento`, `descricao`, `ideditora`, `idautor`, `imagem`, `preco`) VALUES
(11, 'It: A Coisa', '1986-09-15', 'Um dos livros mais assustadores de Stephen King, explorando o terror psicológico.', 7, 7, 'livro11', 69.90),
(12, 'O Oceano no Fim do Caminho', '2013-06-18', 'Uma fantasia poética e sombria de Neil Gaiman.', 8, 8, 'livro12', 45.90),
(13, 'Coraline', '2002-01-01', 'Uma história fascinante e assustadora para todas as idades.', 8, 8, 'livro13', 39.90),
(14, 'O Iluminado', '1977-01-28', 'Um clássico do terror de Stephen King, ambientado em um hotel assombrado.', 7, 7, 'livro14', 59.90);

-- Inserindo Mais Estoque
INSERT INTO `estoque` (`idestoque`, `quantidade`, `produto_idproduto`) VALUES
(11, 50, 11),
(12, 30, 12),
(13, 40, 13),
(14, 35, 14);

-- Relacionando Novos Produtos às Categorias
INSERT INTO `produto_categoria` (`produto_idproduto`, `categoria_idcategoria`) VALUES
(11, 4), -- It: A Coisa -> Terror
(12, 6), -- O Oceano no Fim do Caminho -> Ficção Fantástica
(13, 6), -- Coraline -> Ficção Fantástica
(14, 4); -- O Iluminado -> Terror

INSERT INTO usuario (nome, cpf, is_associado)
SELECT nome, cpf, 1
FROM associado
WHERE idassociado IN (1, 2, 3, 4);

CREATE EVENT sincronizar_associados
ON SCHEDULE EVERY 1 MINUTE
DO
  INSERT INTO usuario (nome, cpf, is_associado, senha)
  SELECT nome, cpf, 1, senha FROM associado WHERE is_synced = 0;


INSERT INTO produto (idproduto, nome, data_lancamento, descricao, ideditora, idautor, imagem, preco) VALUES
(15, 'Lançamento 1', '2024-08-01', 'Descrição do Lançamento 1.', 1, 1, 'lancamento1.jpg', 59.90),
(16, 'Lançamento 2', '2024-09-15', 'Descrição do Lançamento 2.', 2, 2, 'lancamento2.jpg', 49.90),
(17, 'Lançamento 3', '2024-10-20', 'Descrição do Lançamento 3.', 3, 3, 'lancamento3.jpg', 39.90);

-- Inserindo Livros em Pré-venda
INSERT INTO produto (idproduto, nome, data_lancamento, descricao, ideditora, idautor, imagem, preco) VALUES
(18, 'Pré-venda 1', '2025-01-10', 'Descrição do Pré-venda 1.', 4, 4, 'prevenda1.jpg', 69.90),
(19, 'Pré-venda 2', '2025-02-15', 'Descrição do Pré-venda 2.', 5, 5, 'prevenda2.jpg', 59.90),
(20, 'Pré-venda 3', '2025-03-20', 'Descrição do Pré-venda 3.', 6, 6, 'prevenda3.jpg', 49.90);

-- Inserindo Estoque para os novos produtos
INSERT INTO estoque (idestoque, quantidade, produto_idproduto) VALUES
(15, 100, 15),
(16, 100, 16),
(17, 100, 17),
(18, 100, 18),
(19, 100, 19),
(20, 100, 20);

-- Relacionando Novos Produtos às Categorias
INSERT INTO produto_categoria (produto_idproduto, categoria_idcategoria) VALUES
(15, 1), -- Lançamento 1 -> Literatura Brasileira
(16, 2), -- Lançamento 2 -> Fantasia
(17, 3), -- Lançamento 3 -> Distopia
(18, 1), -- Pré-venda 1 -> Literatura Brasileira
(19, 2), -- Pré-venda 2 -> Fantasia
(20, 3); -- Pré-venda 3 -> Distopia

-- Inserindo Editoras
INSERT INTO `editora` (`ideditora`, `nome`, `data_criacao`, `descricao`, `imagem`) VALUES
(9, 'W. W. Norton & Company', '1923-01-01', 'Editora renomada por suas publicações literárias e acadêmicas.', 'editora9'),
(10, 'Random House Large Print Publishing', '1927-01-01', 'Famosa por publicações em larga escala e livros de ficção.', 'editora10');

-- Inserindo Autores
INSERT INTO `autor` (`idautor`, `nome`, `data_nascimento`, `descricao`, `imagem`) VALUES
(9, 'Olivia Laing', '1977-04-15', 'Autora premiada e crítica literária britânica.', 'autor9'),
(10, 'Elif Shafak', '1971-10-25', 'Romancista turco-britânica e Booker Prize finalist.', 'autor10');

-- Inserindo Categorias
INSERT INTO `categoria` (`idcategoria`, `nome`) VALUES
(7, 'História Cultural'),
(8, 'Ficção Histórica'),
(9, 'Clima e Ecologia');

-- Inserindo Livros (Produtos)
-- Inserindo Livros (Produtos)
INSERT INTO `produto` (`idproduto`, `nome`, `data_lancamento`, `descricao`, `ideditora`, `idautor`, `imagem`, `preco`) VALUES
(15, 'The Garden Against Time: In Search of a Common Paradise', '2024-06-25', 'Uma reflexão sobre jardins como lugares de encontro e descoberta.', 9, 9, 'livro15', 70.89),
(16, 'There Are Rivers in the Sky: A novel', '2024-08-27', 'Romance que conecta vidas através dos séculos e rios históricos.', 10, 10, 'livro16', 65.2);

-- Inserindo Estoque
INSERT INTO `estoque` (`idestoque`, `quantidade`, `produto_idproduto`) VALUES
(15, 25, 15),
(16, 15, 16);

-- Relacionando Produtos às Categorias
INSERT INTO `produto_categoria` (`produto_idproduto`, `categoria_idcategoria`) VALUES
(15, 7),
(15, 9),
(16, 8);
