<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['tipo_usuario'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

if ($tipo_usuario == 'associado') {
    $stmt = $conexao->prepare("SELECT a.nome, a.cpf FROM associado a INNER JOIN usuario u ON a.cpf = u.cpf WHERE a.idassociado = ?");
    $stmt->execute([$id]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);
    $nome = $dados['nome'];
    $cpf = $dados['cpf'];
    $senha = $dados['senha'];
} else {
    $stmt = $conexao->prepare("SELECT nome, cpf FROM usuario WHERE idusuario = ?");
    $stmt->execute([$id]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);
    $nome = $dados['nome'];
    $cpf = $dados['cpf'];
    $senha = ''; 
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dados Pessoais - GhostBooks</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>
    <main class="dados-pessoais-container">
        <h2>Editar Dados Pessoais</h2>
        <form method="post" action="processa_dados_pessoais.php">
            <div class="input-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            <div class="input-group">
                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha">
            </div>
            <input type="hidden" name="cpf_ant" value="<?php echo htmlspecialchars($cpf); ?>">
            <button type="submit">Salvar</button>
        </form>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>