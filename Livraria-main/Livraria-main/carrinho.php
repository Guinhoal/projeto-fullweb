<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    echo "<script>alert('Por favor, faça login para continuar.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'cliente') {
    echo "<script>alert('Associados não podem acessar o carrinho.'); window.location.href='index.php';</script>";
    exit();
}

$idusuario = $_SESSION['id'];

// Obter o carrinho do usuário
$stmt = $conexao->prepare("SELECT idcarrinho FROM carrinho WHERE usuario_idusuario = :idusuario");
$stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$stmt->execute();
$carrinho = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$carrinho) {
    echo "<script>alert('Seu carrinho está vazio.'); window.location.href='index.php';</script>";
    exit();
}

$idcarrinho = $carrinho['idcarrinho'];

// Obter os produtos do carrinho
$stmt = $conexao->prepare("SELECT cp.*, p.nome, p.imagem, p.preco FROM carrinho_produto cp JOIN produto p ON cp.produto_idproduto = p.idproduto WHERE cp.carrinho_idcarrinho = :idcarrinho");
$stmt->bindParam(':idcarrinho', $idcarrinho, PDO::PARAM_INT);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
$total_creditos = 0;
$taxa_creditos = 15; 

foreach ($produtos as $produto) {
    $subtotal = $produto['quantidade'] * $produto['preco'];
    $total += $subtotal;
    $total_creditos += $subtotal * $taxa_creditos;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - GhostBooks</title>
    <link rel="stylesheet" href="styleCarrinho.css">
    <link rel="stylesheet" href="form.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>

    <main class="caixa">
        <h1>Seu Carrinho</h1>
        <?php if (count($produtos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td>
                                <img src="imagens-produto/<?php echo htmlspecialchars($produto['imagem']); ?>.png" alt="<?php echo htmlspecialchars($produto['nome']); ?>" width="50">
                                <?php echo htmlspecialchars($produto['nome']); ?>
                            </td>
                            <td><?php echo $produto['quantidade']; ?></td>
                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($produto['quantidade'] * $produto['preco'], 2, ',', '.'); ?></td>
                            <td>
                            <a href="remover_carrinho.php?id=<?php echo $produto['idcarrinho_produto']; ?>" class="btn-remover">Remover</a>

                            </td>
                        </tr>
                        <?php $total += $produto['quantidade'] * $produto['preco']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h2>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h2>
            <h2>Total em Créditos: <?php echo $total_creditos; ?> créditos</h2>
            <a href="comprar.php?etapa=1" class="btn-prosseguir">Prosseguir com a Compra</a>
        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
        <?php endif; ?>
    </main>
    <?php include ("footer.php");?>
</body>
</html>