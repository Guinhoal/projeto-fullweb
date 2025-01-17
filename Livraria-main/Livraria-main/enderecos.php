<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['tipo_usuario'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

if ($tipo_usuario == 'cliente') {
    $stmt = $conexao->prepare("SELECT * FROM endereco_usuario WHERE usuario_idusuario = ?");
    $stmt->execute([$id]);
    $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else if ($tipo_usuario == 'associado') {
    $stmt = $conexao->prepare("SELECT * FROM endereco_associado WHERE associado_idassociado = ?");
    $stmt->execute([$id]);
    $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Endereços - GhostBooks</title>
    <link rel="stylesheet" href="stylePerfil.css">
</head>
<body>
    <div class="voltar-inicio">
        <a href="perfil.php">Voltar</a>
    </div>
    <main class="perfil-container">
        <h2>Gerenciar Endereços</h2>
        <?php if (count($enderecos) < 3) { ?>
            <form method="post" action="processa_enderecos.php">
                <input type="hidden" name="acao" value="adicionar">
                <div class="input-group">
                    <label for="pais">País:</label>
                    <input type="text" id="pais" name="pais" required>
                </div>
                <div class="input-group">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" required>
                </div>
                <div class="input-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" required>
                </div>
                <div class="input-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>
                <button type="submit">Adicionar Endereço</button>
            </form>
        <?php } ?>
        <h3>Endereços Cadastrados</h3>
        <?php foreach ($enderecos as $endereco) { ?>
            <form method="post" action="processa_enderecos.php">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="idendereco" value="<?php echo $tipo_usuario == 'cliente' ? $endereco['idendereco_usuario'] : $endereco['idendereco_associado']; ?>">
                <div class="input-group">
                    <label for="pais">País:</label>
                    <input type="text" id="pais" name="pais" value="<?php echo htmlspecialchars($endereco['pais']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($endereco['estado']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($endereco['cidade']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($endereco['endereco']); ?>" required>
                </div>
                <button type="submit">Salvar Alterações</button>
            </form>
        <?php } ?>
    </main>
</body>
</html>