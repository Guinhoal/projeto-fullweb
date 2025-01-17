<?php
session_start();
require 'conexao.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = intval($_GET['id']);
$stmt = $conexao->prepare("SELECT p.*, a.nome AS autor_nome, a.descricao AS autor_descricao FROM produto p JOIN autor a ON p.idautor = a.idautor WHERE p.idproduto = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header('Location: index.php');
    exit();
}

$preco_creditos = $produto['preco'] * 15;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($produto['nome']); ?> - GhostBooks</title>
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+N9+YJ2du9Bj5Z2CFAc8QpixS4OdTf9ZGTyNfCE" crossorigin="anonymous" />
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>
    <main class="livro-main">
        <div class="livro-imagem-container">
            <img src="imagens-produto/<?php echo htmlspecialchars($produto['imagem']); ?>.png" alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="livro-imagem">
        </div>
        <div class="livro-detalhes">
            <div class="livro-info">
                <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
                <p class="autor">Autor: <?php echo htmlspecialchars($produto['autor_nome']); ?></p>
                <p class="descricao"><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
                <p class="preco">Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                <p class="preco-creditos">Preço em Créditos: <?php echo $preco_creditos; ?> créditos</p>
                <div class="livro-acoes">
                    <a href="adicionar_carrinho.php?id=<?php echo $produto['idproduto']; ?>" class="btn-adicionar">
                        <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                    </a>
                    <a href="comprar_agora.php?etapa=1&produto_id=<?php echo $produto['idproduto']; ?>" class="btn-comprar">
    <i class="fas fa-credit-card"></i> Compre Agora
</a>
                </div>
                <div class="autor-info">
                    <a href="autor.php?id=<?php echo $produto['idautor']; ?>">Veja mais sobre o autor</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>