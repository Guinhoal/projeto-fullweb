<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Senha - GhostBooks</title>
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
        <?php
        session_start();
        if (!isset($_SESSION['reset_token']) || !isset($_GET['token']) || 
            $_SESSION['reset_token'] !== $_GET['token']) {
            echo '<div class="erro">Link de recuperação inválido ou expirado.</div>';
            exit();
        }
        ?>
        <form action="atualizar_senha.php" method="post" class="login-form">
            <h2>Digite sua Nova Senha</h2>
            <div class="input-group">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
            </div>
            <div class="input-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
            </div>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <button type="submit">Alterar Senha</button>
        </form>
    </main>
    <?php include ("footer.php");?>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const senha = document.getElementById('nova_senha').value;
            const confirmar = document.getElementById('confirmar_senha').value;
            
            if (senha !== confirmar) {
                e.preventDefault();
                alert('As senhas não coincidem!');
            }
        });
    </script>
</body>
</html>