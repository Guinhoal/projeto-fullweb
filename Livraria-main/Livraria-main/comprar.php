<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'cliente') {
    echo "<script>alert('Associados não podem realizar compras.'); window.location.href='index.php';</script>";
    exit();
}

$idusuario = $_SESSION['id'];
$etapa = isset($_GET['etapa']) ? intval($_GET['etapa']) : 1;
$total = 0;
$frete = 10.00; 
$taxa_creditos = 15; 

$stmt = $conexao->prepare("SELECT idcarrinho FROM carrinho WHERE usuario_idusuario = :idusuario");
$stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$stmt->execute();
$carrinho = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$carrinho) {
    echo "<script>alert('Seu carrinho está vazio.'); window.location.href='index.php';</script>";
    exit();
}

$idcarrinho = $carrinho['idcarrinho'];

$stmt = $conexao->prepare("SELECT cp.*, p.nome, p.preco FROM carrinho_produto cp JOIN produto p ON cp.produto_idproduto = p.idproduto WHERE cp.carrinho_idcarrinho = :idcarrinho");
$stmt->bindParam(':idcarrinho', $idcarrinho, PDO::PARAM_INT);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($produtos as $produto) {
    $total += $produto['quantidade'] * $produto['preco'];
}

$total_com_frete = $total + $frete;
$total_creditos = $total_com_frete * $taxa_creditos;

$stmt = $conexao->prepare("SELECT * FROM endereco_usuario WHERE usuario_idusuario = :idusuario");
$stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$stmt->execute();
$enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conexao->prepare("SELECT creditos FROM creditos WHERE idusuario = :idusuario");
$stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$stmt->execute();
$creditos = $stmt->fetch(PDO::FETCH_ASSOC)['creditos'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($etapa === 1) {
        $endereco_id = intval($_POST['endereco_id']);
        if ($endereco_id === 0) {
            $pais = $_POST['pais'];
            $estado = $_POST['estado'];
            $cidade = $_POST['cidade'];
            $endereco = $_POST['endereco'];
            $stmt = $conexao->prepare("INSERT INTO endereco_usuario (pais, estado, cidade, endereco, usuario_idusuario) VALUES (:pais, :estado, :cidade, :endereco, :idusuario)");
            $stmt->bindParam(':pais', $pais);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':cidade', $cidade);
            $stmt->bindParam(':endereco', $endereco);
            $stmt->bindParam(':idusuario', $idusuario);
            $stmt->execute();
            $endereco_id = $conexao->lastInsertId();
        }
        $_SESSION['endereco_id'] = $endereco_id;
        header('Location: comprar.php?etapa=2');
        exit();
    } elseif ($etapa === 2) {
        $forma_pagamento = $_POST['forma_pagamento'];
        $_SESSION['forma_pagamento'] = $forma_pagamento;
        header('Location: comprar.php?etapa=3');
        exit();
    } elseif ($etapa === 3) {
        $endereco_id = $_SESSION['endereco_id'];
        $forma_pagamento = $_SESSION['forma_pagamento'];

        if ($forma_pagamento === 'credito' && $creditos < $total_creditos) {
            echo "<script>alert('Você não tem créditos suficientes para esta compra.'); window.location.href='comprar.php?etapa=2';</script>";
            exit();
        }

        $stmt = $conexao->prepare("INSERT INTO venda (preco, estado, usuario_idusuario, forma_pagamento, data_venda, endereco_id) VALUES (:preco, 'Pagamento Confirmado - Aguardando saída do Pacote', :usuario_idusuario, :forma_pagamento, NOW(), :endereco_id)");
        $stmt->bindParam(':preco', $total_com_frete);
        $stmt->bindParam(':usuario_idusuario', $idusuario);
        $stmt->bindParam(':forma_pagamento', $forma_pagamento);
        $stmt->bindParam(':endereco_id', $endereco_id);
        $stmt->execute();
        $idvenda = $conexao->lastInsertId();
    
        $stmt = $conexao->prepare("INSERT INTO venda_produto (venda_idvenda, produto_idproduto, quantidade, preco_unitario) VALUES (:venda_idvenda, :produto_idproduto, :quantidade, :preco_unitario)");
        foreach ($produtos as $produto) {
            $stmt->bindValue(':venda_idvenda', $idvenda, PDO::PARAM_INT);
            $stmt->bindValue(':produto_idproduto', $produto['produto_idproduto'], PDO::PARAM_INT);
            $stmt->bindValue(':quantidade', $produto['quantidade'], PDO::PARAM_INT);
            $stmt->bindValue(':preco_unitario', $produto['preco']);
            $stmt->execute();

            $stmt_estoque = $conexao->prepare("UPDATE estoque SET quantidade = quantidade - :quantidade WHERE produto_idproduto = :produto_idproduto");
            $stmt_estoque->bindParam(':quantidade', $produto['quantidade'], PDO::PARAM_INT);
            $stmt_estoque->bindParam(':produto_idproduto', $produto['produto_idproduto'], PDO::PARAM_INT);
            $stmt_estoque->execute();
        }

        if ($forma_pagamento === 'credito') {
            $novo_creditos = $creditos - $total_creditos;
            $stmt = $conexao->prepare("UPDATE creditos SET creditos = :creditos WHERE idusuario = :idusuario");
            $stmt->bindParam(':creditos', $novo_creditos, PDO::PARAM_INT);
            $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
            $stmt->execute();
        }
    
        $stmt = $conexao->prepare("DELETE FROM carrinho_produto WHERE carrinho_idcarrinho = :idcarrinho");
        $stmt->bindParam(':idcarrinho', $idcarrinho, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: recibo.php?idvenda=$idvenda");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Comprar - GhostBooks</title>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="styleCompra.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script>
        function toggleNewAddressFields() {
            const newAddressFields = document.getElementById('new-address-fields');
            const newAddressRadio = document.getElementById('new-address-radio');
            if (newAddressRadio.checked) {
                newAddressFields.style.display = 'block';
                document.getElementById('pais').required = true;
                document.getElementById('estado').required = true;
                document.getElementById('cidade').required = true;
                document.getElementById('endereco').required = true;
            } else {
                newAddressFields.style.display = 'none';
                document.getElementById('pais').required = false;
                document.getElementById('estado').required = false;
                document.getElementById('cidade').required = false;
                document.getElementById('endereco').required = false;
            }
        }
    </script>
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>

    <main class="caixa">
        <?php if ($etapa === 1): ?>
            <h1>Selecione ou Cadastre um Endereço</h1>
            <form method="post">
                <?php if (count($enderecos) > 0): ?>
                    <h2>Endereços Cadastrados</h2>
                    <?php foreach ($enderecos as $endereco): ?>
                        <div>
                            <input type="radio" name="endereco_id" value="<?php echo $endereco['idendereco_usuario']; ?>" onclick="toggleNewAddressFields()" required>
                            <?php echo htmlspecialchars($endereco['endereco']) . ', ' . htmlspecialchars($endereco['cidade']) . ', ' . htmlspecialchars($endereco['estado']) . ', ' . htmlspecialchars($endereco['pais']); ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <h2>Ou Cadastre um Novo Endereço</h2>
                <div>
                    <input type="radio" name="endereco_id" value="0" id="new-address-radio" onclick="toggleNewAddressFields()" required> Novo Endereço
                </div>
                <div id="new-address-fields" style="display: none;">
                    <div>
                        <label for="pais">País:</label>
                        <input type="text" id="pais" name="pais">
                    </div>
                    <div>
                        <label for="estado">Estado:</label>
                        <input type="text" id="estado" name="estado">
                    </div>
                    <div>
                        <label for="cidade">Cidade:</label>
                        <input type="text" id="cidade" name="cidade">
                    </div>
                    <div>
                        <label for="endereco">Endereço:</label>
                        <input type="text" id="endereco" name="endereco">
                    </div>
                </div>
                <button type="submit">Continuar</button>
            </form>
        <?php elseif ($etapa === 2): ?>
            <h1>Selecione a Forma de Pagamento</h1>
            <p>Seus Créditos: <?php echo $creditos; ?> créditos</p>
            <form method="post">
                <div>
                    <label>
                        <input type="radio" name="forma_pagamento" value="pix" required> Pix
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="forma_pagamento" value="cartao" required> Cartão de Crédito
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="forma_pagamento" value="credito" required> Créditos
                    </label>
                </div>
                <button type="submit">Continuar</button>
            </form>
        <?php elseif ($etapa === 3): ?>
            <h1>Confirme sua Compra</h1>
            <p>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
            <p>Frete: R$ <?php echo number_format($frete, 2, ',', '.'); ?></p>
            <p>Total com Frete: R$ <?php echo number_format($total_com_frete, 2, ',', '.'); ?></p>
            <p>Total em Créditos: <?php echo $total_creditos; ?> créditos</p>
            <form method="post">
                <button type="submit">Confirmar Compra</button>
            </form>
        <?php endif; ?>
    </main>
    <?php include ("footer.php");?>
</body>
</html>