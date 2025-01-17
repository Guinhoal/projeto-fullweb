<?php
session_start();
require 'conexao.php';

$termo = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Busca - GhostBooks</title>
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
    <script defer src="script.js"></script>
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>
    <main class="resultados-busca">
        <h1>Resultados da Busca por "<?php echo $termo; ?>"</h1>
        <div class="nova-livros-container">
            <h2>Livros</h2>
            <?php
            $stmt = $conexao->prepare("SELECT * FROM produto WHERE nome LIKE :termo");
            $stmt->bindValue(':termo', '%' . $termo . '%');
            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($produtos) {
                foreach ($produtos as $produto) {
                    echo '<div class="novo-livro">';
                    echo '<a href="livro.php?id=' . $produto['idproduto'] . '">';
                    echo '<img src="imagens-produto/' . htmlspecialchars($produto['imagem']) . '.png" alt="' . htmlspecialchars($produto['nome']) . '">';
                    echo '<h3>' . htmlspecialchars($produto['nome']) . '</h3>';
                    echo '<p>' . htmlspecialchars($produto['descricao']) . '</p>';
                    echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum livro encontrado.</p>';
            }
            ?>
        </div>
        <div class="nova-autores-container">
            <h2>Autores</h2>
            <?php
            $stmt = $conexao->prepare("SELECT * FROM autor WHERE nome LIKE :termo");
            $stmt->bindValue(':termo', '%' . $termo . '%');
            $stmt->execute();
            $autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($autores) {
                foreach ($autores as $autor) {
                    echo '<div class="novo-autor">';
                    echo '<a href="autor.php?id=' . $autor['idautor'] . '">';
                    echo '<img src="imagens-autores/' . htmlspecialchars($autor['imagem']) . '.png" alt="' . htmlspecialchars($autor['nome']) . '" class="novo-autor-imagem">';
                    echo '<h3>' . htmlspecialchars($autor['nome']) . '</h3>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum autor encontrado.</p>';
            }
            ?>
        </div>
        <div class="nova-editoras-container">
            <h2>Editoras</h2>
            <?php
            $stmt = $conexao->prepare("SELECT * FROM editora WHERE nome LIKE :termo");
            $stmt->bindValue(':termo', '%' . $termo . '%');
            $stmt->execute();
            $editoras = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($editoras) {
                foreach ($editoras as $editora) {
                    echo '<div class="nova-editora">';
                    echo '<a href="editora.php?id=' . $editora['ideditora'] . '">';
                    echo '<img src="imagens-editora/' . htmlspecialchars($editora['imagem']) . '.png" alt="' . htmlspecialchars($editora['nome']) . '" class="nova-editora-imagem">';
                    echo '<h3>' . htmlspecialchars($editora['nome']) . '</h3>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhuma editora encontrada.</p>';
            }
            ?>
        </div>
    </main>
</body>
</html>