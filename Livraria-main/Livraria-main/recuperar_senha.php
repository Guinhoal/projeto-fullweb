<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - GhostBooks</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
</head>
<body>
    <header>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>
    </header>

    <main class="login-container">
        <form action="processar_recuperar_senha.php" method="post" class="login-form">
            <?php
            session_start();
            if (isset($_SESSION['erro'])) {
                echo '<div class="erro">' . htmlspecialchars($_SESSION['erro']) . '</div>';
                unset($_SESSION['erro']);
            }
            ?>
            <h2>Recuperar Senha</h2>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" maxlength="11" required>
            </div>
            <button type="submit">Recuperar Senha</button>
            <p><a href="login.php">Voltar ao Login</a></p>
        </form>
    </main>
    <?php include ("footer.php");?>
</body>
</html>