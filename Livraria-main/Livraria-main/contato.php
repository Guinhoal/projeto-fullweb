<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['tipo_usuario'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$email = $telefone = '';

if ($tipo_usuario == 'cliente') {
    $stmt = $conexao->prepare("SELECT email, telefone FROM usuario_contato WHERE usuario_idusuario = ?");
    $stmt->execute([$id]);
    $contato = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $contato['email'];
    $telefone = $contato['telefone'];
} else if ($tipo_usuario == 'associado') {
    $stmt = $conexao->prepare("SELECT email, telefone FROM associado_contato WHERE associado_idassociado = ?");
    $stmt->execute([$id]);
    $contato = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $contato['email'];
    $telefone = $contato['telefone'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Contato - GhostBooks</title>
    <link rel="stylesheet" href="stylePerfil.css">
</head>
<body>
    <div class="voltar-inicio">
        <a href="perfil.php">Voltar</a>
    </div>
    <main class="perfil-container">
        <h2>Contato</h2>
        <form method="post" action="processa_contato.php">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="input-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>" required>
            </div>
            <button type="submit">Atualizar</button>
        </form>
    </main>
</body>
</html>