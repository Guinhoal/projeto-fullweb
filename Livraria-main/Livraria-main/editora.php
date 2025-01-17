<?php
session_start();
require 'conexao.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = intval($_GET['id']);

$stmt = $conexao->prepare("SELECT * FROM editora WHERE ideditora = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$editora = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$editora) {
    header('Location: index.php');
    exit();
}

$stmt = $conexao->prepare("SELECT * FROM produto WHERE ideditora = :ideditora");
$stmt->bindParam(':ideditora', $id, PDO::PARAM_INT);
$stmt->execute();
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($editora['nome']); ?> - GhostBooks</title>
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+N9+YJ2du9Bj5Z2CFAc8QpixS4OdTf9ZGTyNfCE" crossorigin="anonymous" />
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>
    <main class="editora-main">
        <div class="editora-detalhes">
            <div class="editora-imagem-container">
                <img src="imagens-editora/<?php echo htmlspecialchars($editora['imagem']); ?>.png" alt="<?php echo htmlspecialchars($editora['nome']); ?>" class="editora-imagem">
            </div>
            <div class="editora-info">
                <h1><?php echo htmlspecialchars($editora['nome']); ?></h1>
                <p><strong>Data de Criação:</strong> <?php echo date("d/m/Y", strtotime($editora['data_criacao'])); ?></p>
                <p><?php echo nl2br(htmlspecialchars($editora['descricao'])); ?></p>
            </div>
        </div>
        <div class="livros-editora">
            <h2>Livros publicados por <?php echo htmlspecialchars($editora['nome']); ?></h2>
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
                    echo '<p>Nenhum livro encontrado para esta editora.</p>';
                }
                ?>
            </div>
        </div>
    </main>
</body>
</html>