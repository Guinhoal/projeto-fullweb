<?php
session_start();
require 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GhostBooks</title>
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
    <script defer src="script.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <section>
        <div class="container-inicial">
            <div>
                <img src="imagens/favicon.png" alt="img-inicial" id="img-inicial">
            </div>
        </div>
    </section>
    <div class="pos-inicio">
        <section>
            <div class="container-home">
                <header>
                    <nav>
                        <ul id="primeira-lista">
                            <li>
                                <img src="imagens/favicon.png" alt="Logo da loja" id="logo">
                            </li>
                            <li>
                                <form action="buscar.php" method="get">
                                    <input type="text" name="q" id="busca" placeholder="Títulos, Autores, Gêneros e Editoras">
                                    <button type="submit">
                                        <img src="imagens/lupa.png" alt="lupa" id="lupa">
                                    </button>
                                </form>
                            </li>
                            <li>
                                <?php
                                if (isset($_SESSION['nome'])) {
                                    $primeiro_nome = explode(' ', $_SESSION['nome'])[0];
                                    ?>
                                    <a href="perfil.php">
                                        <img src="imagens/icon.png" alt="icon" id="icon">
                                        <div class="entre" id="entre">
                                            <span>Bem-vindo, <br> <?php echo htmlspecialchars($primeiro_nome); ?></span>
                                        </div>
                                    </a>
                                    <?php
                                } else {
                                    ?>
                                    <a href="login.php">
                                        <img src="imagens/icon.png" alt="icon" id="icon">
                                        <div class="entre" id="entre">
                                            <span>Entre ou <br>Cadastre-se</span>
                                        </div>
                                    </a>
                                    <?php
                                }
                                ?>
                            </li>
                            <li>
                                <a href="carrinho.php">
                                    <img src="imagens/carrinho.png" alt="carrinho" id="carrinho">
                                </a>
                            </li>
                        </ul>
                        <ul id="segunda-lista">
                            <li><a href="#div1" id="d1" class="estilo">INÍCIO</a></li>
                            <li><a href="#div2" id="d2" class="estilo">LANÇAMENTOS</a></li>
                            <li><a href="#div3" id="d3" class="estilo">BEST SELLERS</a></li>
                            <li><a href="#div4" id="d4" class="estilo">PRÉ-VENDAS</a></li>
                            <li><a href="#div5" id="d5" class="estilo">AUTORES</a></li>
                            <li><a href="#div6" id="d6" class="estilo">EDITORAS</a></li>
                            <li><a href="#div7" id="d7" class="estilo">PROMOÇÕES</a></li>
                            <li><a href="#div8" id="d8" class="jogo">CRÉDITOS</a></li>
                        </ul>
                    </nav>
                </header>
            </div>
        </section>
        <main>
            <div id="div1">
                <!-- Espaço para exibir os livros -->
                <div class="nova-livros-container">
                    <?php
                    $stmt = $conexao->prepare("SELECT * FROM produto");
                    $stmt->execute();
                    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    ?>
                </div>
            </div>
            <div id="div2">
            </div>
            <div id="div3">
            </div>
            <div id="div4">
            </div>
            <div id="div5">
                <div class="nova-autores-container">
                    <?php
                    $stmt = $conexao->prepare("SELECT * FROM autor");
                    $stmt->execute();
                    $autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($autores as $autor) {
                        echo '<div class="novo-autor">';
                        echo '<a href="autor.php?id=' . $autor['idautor'] . '">';
                        echo '<img src="imagens-autores/' . htmlspecialchars($autor['imagem']) . '.png" alt="' . htmlspecialchars($autor['nome']) . '" class="novo-autor-imagem">';
                        echo '<h3>' . htmlspecialchars($autor['nome']) . '</h3>';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <div id="div6">
                <div class="nova-editoras-container">
                    <?php
                    $stmt = $conexao->prepare("SELECT * FROM editora");
                    $stmt->execute();
                    $editoras = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($editoras as $editora) {
                        echo '<div class="nova-editora">';
                        echo '<a href="editora.php?id=' . $editora['ideditora'] . '">';
                        echo '<img src="imagens-editora/' . htmlspecialchars($editora['imagem']) . '.png" alt="' . htmlspecialchars($editora['nome']) . '" class="nova-editora-imagem">';
                        echo '<h3>' . htmlspecialchars($editora['nome']) . '</h3>';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <div id="div7">
            </div>
            <div id="div8">
                <?php
                if (isset($_SESSION['nome'])) {
                    ?>
                    <h1>Jogue para ganhar</h1>
                    <img src="imagens-jogo/dindin.png" alt="dindin">
                    <a href="indexJogo.html">
                        <button>Jogue para ganhar créditos</button>
                    </a>
                    <?php
                } else {
                    ?>
                    <h1>Conecte-se para ganhar créditos</h1>
                    <img src="imagens-jogo/favicon.png" alt="azul" id="azul">
                    <a href="login.php">
                        <button>Faça login</button>
                    </a>
                    <?php
                }
                ?>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>