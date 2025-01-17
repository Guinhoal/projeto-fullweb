<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - GhostBooks</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>

    <main class="login-container">
        <form action="processa_login.php" method="post" class="login-form">
            <?php
            if (isset($_SESSION['erro'])) {
                echo '<div class="erro">' . htmlspecialchars($_SESSION['erro']) . '</div>';
                unset($_SESSION['erro']);
            }
            if (isset($_SESSION['mensagem'])) {
                echo '<div class="sucesso">' . htmlspecialchars($_SESSION['mensagem']) . '</div>';
                unset($_SESSION['mensagem']);
            }
            ?>
            <h2>Bem-vindo de volta!</h2>
            <div class="input-group">
                <label for="tipo_usuario">Entrar como:</label>
                <select name="tipo_usuario" id="tipo_usuario" required>
                    <option value="cliente">Cliente</option>
                    <option value="associado">Associado</option>
                </select>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
            <p>Esqueceu sua senha? <a href="recuperar_senha.php">Recuperar</a></p>
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        </form>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>