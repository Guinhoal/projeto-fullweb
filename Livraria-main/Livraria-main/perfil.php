<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['tipo_usuario'])) {
    header('Location: login.php');
    exit();
}

$idusuario = $_SESSION['id'];

$stmt = $conexao->prepare("SELECT creditos FROM creditos WHERE idusuario = ?");
$stmt->execute([$idusuario]);
$creditos = $stmt->fetch(PDO::FETCH_ASSOC);
$creditos = $creditos ? $creditos['creditos'] : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil - GhostBooks</title>
    <link rel="stylesheet" href="stylePerfil.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>

    <main class="perfil-container">
        <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?></h2>
        <p class="creditos">Créditos: <?php echo htmlspecialchars($creditos); ?></p>
        <nav class="perfil-nav">
            <ul>
                <?php if ($_SESSION['tipo_usuario'] == 'cliente') { ?>
                    <li><a href="pedidos.php">Ver Pedidos</a></li>
                <?php } ?>
                <li><a href="dados_pessoais.php">Dados Pessoais</a></li>
                <li><a href="enderecos.php">Endereços</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php if ($_SESSION['tipo_usuario'] == 'associado') { ?>
                    <li><a href="ver_pedidos.php">Ver Pedidos</a></li>
                    <li><a href="gerenciar.php">Gerenciar</a></li>
                <?php } ?>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>