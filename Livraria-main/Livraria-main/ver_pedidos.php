<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'associado') {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];
$cargo = $_SESSION['cargo'];

function listarPedidos($conexao) {
    $stmt = $conexao->prepare("SELECT v.idvenda, v.estado, u.nome AS cliente, v.preco, v.data_venda
                               FROM venda v
                               JOIN usuario u ON v.usuario_idusuario = u.idusuario
                               ORDER BY v.data_venda DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pedido_id']) && isset($_POST['estado'])) {
        $pedido_id = $_POST['pedido_id'];
        $estado = $_POST['estado'];
        $stmt = $conexao->prepare("UPDATE venda SET estado = ? WHERE idvenda = ?");
        $stmt->execute([$estado, $pedido_id]);
    }
}

$pedidos = listarPedidos($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ver Pedidos - GhostBooks</title>
    <link rel="stylesheet" href="styleAdm.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="voltar-inicio">
        <a href="perfil.php">Voltar</a>
    </div>
    <main class="container">
        <h1>Ver e Atualizar Pedidos</h1>
        <?php if (empty($pedidos)) { ?>
            <p>Nenhum pedido disponível.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['idvenda']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($pedido['data_venda'])); ?></td>
                            <td>R$ <?php echo number_format($pedido['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            <td class="acoes">
                                <a href="recibo.php?idvenda=<?php echo $pedido['idvenda']; ?>">Ver Recibo</a>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="pedido_id" value="<?php echo htmlspecialchars($pedido['idvenda']); ?>">
                                    <select name="estado">
                                        <option value="Pedido saiu para a Entrega">Pedido saiu para a Entrega</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                    <button type="submit">Atualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>