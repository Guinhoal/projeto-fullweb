<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$idusuario = $_SESSION['id'];

// Obter os pedidos do usuário
$stmt = $conexao->prepare("SELECT * FROM venda WHERE usuario_idusuario = :idusuario ORDER BY data_venda DESC");
$stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_recebimento'])) {
    $idvenda = intval($_POST['idvenda']);
    $stmt = $conexao->prepare("UPDATE venda SET estado = 'Entregue - Concluído' WHERE idvenda = :idvenda AND usuario_idusuario = :idusuario");
    $stmt->bindParam(':idvenda', $idvenda, PDO::PARAM_INT);
    $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
    $stmt->execute();
    header('Location: pedidos.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos - GhostBooks</title>
    <link rel="stylesheet" href="stylePedidos.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>

    <main class="pedidos-container">
        <h1>Meus Pedidos</h1>
        <?php if (count($pedidos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID do Pedido</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['idvenda']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($pedido['data_venda'])); ?></td>
                            <td>R$ <?php echo number_format($pedido['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            <td>
                                <a href="recibo.php?idvenda=<?php echo $pedido['idvenda']; ?>">Ver Recibo</a>
                                <a href="status_compra.php?idvenda=<?php echo $pedido['idvenda']; ?>">Ver Status</a>
                                <?php if ($pedido['estado'] === 'Pedido saiu para a Entrega'): ?>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="idvenda" value="<?php echo $pedido['idvenda']; ?>">
                                        <button type="submit" name="confirmar_recebimento">Confirmar Recebimento</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Você ainda não fez nenhum pedido.</p>
        <?php endif; ?>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>