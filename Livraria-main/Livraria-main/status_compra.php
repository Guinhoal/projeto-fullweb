<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$idusuario = $_SESSION['id'];

$stmt = $conexao->prepare("SELECT * FROM venda WHERE usuario_idusuario = :idusuario ORDER BY data_venda DESC LIMIT 1");
$stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$stmt->execute();
$venda = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$venda) {
    echo "<script>alert('Nenhuma compra encontrada.'); window.location.href='index.php';</script>";
    exit();
}

$estado = $venda['estado'];
$data_venda = $venda['data_venda'];
$preco = $venda['preco'];
$forma_pagamento = $venda['forma_pagamento'];
$idvenda = $venda['idvenda'];

$stmt = $conexao->prepare("SELECT * FROM endereco_usuario WHERE idendereco_usuario = :endereco_id");
$stmt->bindParam(':endereco_id', $venda['endereco_id'], PDO::PARAM_INT);
$stmt->execute();
$endereco = $stmt->fetch(PDO::FETCH_ASSOC);

$prazo_entrega = date('d/m/Y', strtotime($data_venda . ' + 7 days'));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Status da Compra - GhostBooks</title>
    <link rel="stylesheet" href="styleCompra.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>

    <main class="caixa">
        <h1>Status da Compra</h1>
        <p>ID da Venda: <?php echo htmlspecialchars($idvenda); ?></p>
        <p>Data da Compra: <?php echo date('d/m/Y', strtotime($data_venda)); ?></p>
        <p>Total: R$ <?php echo number_format($preco, 2, ',', '.'); ?></p>
        <p>Forma de Pagamento: <?php echo htmlspecialchars($forma_pagamento); ?></p>
        <p>Status: <?php echo htmlspecialchars($estado); ?></p>
        <p>Prazo de Entrega: <?php echo $prazo_entrega; ?></p>
        <h2>Endereço de Entrega</h2>
        <p><?php echo htmlspecialchars($endereco['endereco']) . ', ' . htmlspecialchars($endereco['cidade']) . ', ' . htmlspecialchars($endereco['estado']) . ', ' . htmlspecialchars($endereco['pais']); ?></p>
        <h2>Produtos</h2>
        <ul>
            <?php
            $stmt = $conexao->prepare("SELECT p.nome FROM venda_produto vp JOIN produto p ON vp.produto_idproduto = p.idproduto WHERE vp.venda_idvenda = :idvenda");
            $stmt->bindParam(':idvenda', $idvenda, PDO::PARAM_INT);
            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($produtos as $produto) {
                echo '<li>' . htmlspecialchars($produto['nome']) . '</li>';
            }
            ?>
        </ul>
    </main>
    <?php include ("footer.php");?>
</body>
</html>