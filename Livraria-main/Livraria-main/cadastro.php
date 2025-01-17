<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro - GhostBooks</title>
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
        <form action="processa_cadastro.php" method="post" class="login-form">
            <?php
            if (isset($_SESSION['erro'])) {
                echo '<div class="erro">' . htmlspecialchars($_SESSION['erro']) . '</div>';
                unset($_SESSION['erro']);
            }
            if (isset($_SESSION['erros'])) {
                foreach ($_SESSION['erros'] as $erro) {
                    echo '<div class="erro">' . htmlspecialchars($erro) . '</div>';
                }
                unset($_SESSION['erros']);
            }
            if (isset($_SESSION['mensagem'])) {
                echo '<div class="sucesso">' . htmlspecialchars($_SESSION['mensagem']) . '</div>';
                unset($_SESSION['mensagem']);
            }
            ?>

            <h2>Criar Conta</h2>
            <div class="input-group">
                <label for="tipo_usuario">Você é:</label>
                <select name="tipo_usuario" id="tipo_usuario" required>
                    <option value="cliente">Cliente</option>
                    <option value="associado">Associado</option>
                </select>
            </div>
            <div class="input-group" id="codigo-container" style="display: none;">
                <label for="codigo_associado">Código do Associado:</label>
                <input type="text" id="codigo_associado" name="codigo_associado">
            </div>
            <div class="input-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="input-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" maxlength="11" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Cadastrar</button>
            <p>Já tem uma conta? <a href="login.php">Entrar</a></p>
        </form>
    </main>
    <?php include("footer.php"); ?>
    <script>
        const tipoUsuarioSelect = document.getElementById('tipo_usuario');
        const codigoContainer = document.getElementById('codigo-container');

        tipoUsuarioSelect.addEventListener('change', function() {
            if (this.value === 'associado') {
                codigoContainer.style.display = 'block';
                document.getElementById('codigo_associado').required = true;
            } else {
                codigoContainer.style.display = 'none';
                document.getElementById('codigo_associado').required = false;
            }
        });
    </script>
</body>

</html>