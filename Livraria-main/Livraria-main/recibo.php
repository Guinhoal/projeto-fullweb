<?php
session_start();
require 'conexao.php';

if (!isset($_GET['idvenda'])) {
    echo "<script>alert('ID da venda não fornecido.'); window.location.href='index.php';</script>";
    exit();
}

$idvenda = intval($_GET['idvenda']);

// Obter os detalhes da venda
$stmt = $conexao->prepare("SELECT v.*, u.nome AS usuario_nome FROM venda v JOIN usuario u ON v.usuario_idusuario = u.idusuario WHERE v.idvenda = :idvenda");
$stmt->bindParam(':idvenda', $idvenda, PDO::PARAM_INT);
$stmt->execute();
$venda = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$venda) {
    echo "<script>alert('Venda não encontrada.'); window.location.href='index.php';</script>";
    exit();
}

$data_venda = $venda['data_venda'];
$preco = $venda['preco'];
$estado = $venda['estado'];
$forma_pagamento = $venda['forma_pagamento'];
$usuario_nome = $venda['usuario_nome'];

// Obter os produtos vendidos
$stmt = $conexao->prepare("SELECT vp.*, p.nome AS produto_nome FROM venda_produto vp JOIN produto p ON vp.produto_idproduto = p.idproduto WHERE vp.venda_idvenda = :idvenda");
$stmt->bindParam(':idvenda', $idvenda, PDO::PARAM_INT);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recibo - GhostBooks</title>
    <link rel="stylesheet" href="styleRecibo.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>

    <main class="caixa">
        <h1>Recibo</h1>
        <p>Data da Compra: <?php echo date('d/m/Y', strtotime($data_venda)); ?></p>
        <p>Total: R$ <?php echo number_format($preco, 2, ',', '.'); ?></p>
        <p>Forma de Pagamento: <?php echo htmlspecialchars($forma_pagamento); ?></p>
        <p>Status: <?php echo htmlspecialchars($estado); ?></p>
        <h2>Produtos</h2>
        <ul>
            <?php foreach ($produtos as $produto): ?>
                <li><?php echo htmlspecialchars($produto['produto_nome']) . ' - Quantidade: ' . $produto['quantidade'] . ' - Preço Unitário: R$ ' . number_format($produto['preco_unitario'], 2, ',', '.'); ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="status_compra.php">Verificar Status da Compra</a>
    </main>
    <?php include ("footer.php");?>
</body>
</html>