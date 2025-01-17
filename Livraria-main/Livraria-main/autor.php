<?php
session_start();
require 'conexao.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = intval($_GET['id']);

$stmt = $conexao->prepare("SELECT * FROM autor WHERE idautor = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$autor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$autor) {
    header('Location: index.php');
    exit();
}

$stmt = $conexao->prepare("SELECT * FROM produto WHERE idautor = :idautor");
$stmt->bindParam(':idautor', $id, PDO::PARAM_INT);
$stmt->execute();
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($autor['nome']); ?> - GhostBooks</title>
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+N9+YJ2du9Bj5Z2CFAc8QpixS4OdTf9ZGTyNfCE" crossorigin="anonymous" />
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>
    <main class="autor-main">
        <div class="autor-detalhes">
            <div class="autor-imagem-container">
                <img src="imagens-autores/<?php echo htmlspecialchars($autor['imagem']); ?>.png" alt="<?php echo htmlspecialchars($autor['nome']); ?>" class="autor-imagem">
            </div>
            <div class="autor-info">
                <h1><?php echo htmlspecialchars($autor['nome']); ?></h1>
                <p><strong>Data de Nascimento:</strong> <?php echo date("d/m/Y", strtotime($autor['data_nascimento'])); ?></p>
                <p><?php echo nl2br(htmlspecialchars($autor['descricao'])); ?></p>
            </div>
        </div>
        <div class="livros-autor">
            <h2>Livros de <?php echo htmlspecialchars($autor['nome']); ?></h2>
            <div class="nova-livros-container">
                <?php
                if ($livros) {
                    foreach ($livros as $livro) {
                        echo '<div class="novo-livro">';
                        echo '<a href="livro.php?id=' . $livro['idproduto'] . '">';
                        echo '<img src="imagens-produto/' . htmlspecialchars($livro['imagem']) . '.png" alt="' . htmlspecialchars($livro['nome']) . '">';
                        echo '<h3>' . htmlspecialchars($livro['nome']) . '</h3>';
                        echo '<p>Preço: R$ ' . number_format($livro['preco'], 2, ',', '.') . '</p>';
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Nenhum livro encontrado para este autor.</p>';
                }
                ?>
            </div>
        </div>
    </main>
</body>
</html>