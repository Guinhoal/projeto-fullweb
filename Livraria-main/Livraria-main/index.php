<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    $stmt = $conexao->prepare("SELECT idusuario, nome, cpf, senha, is_associado FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['id'] = $usuario['idusuario'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['cpf'] = $usuario['cpf'];
        $_SESSION['is_associado'] = $usuario['is_associado'];

        if (!$usuario['is_associado']) {
            $stmt = $conexao->prepare("SELECT * FROM usuario_creditos WHERE idusuario = ?");
            $stmt->execute([$usuario['idusuario']]);
            $creditos = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$creditos) {
                $stmt = $conexao->prepare("INSERT INTO usuario_creditos (idusuario, creditos) VALUES (?, 0)");
                $stmt->execute([$usuario['idusuario']]);
            }
        }

        header('Location: perfil.php');
        exit();
    } else {
        $_SESSION['erro'] = "Email ou senha incorretos.";
        header('Location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GhostBooks</title>
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <section>
            <div class="container-inicial">
                <div>
                    <img src="imagens/favicon.png" alt="img-inicial" id="img-inicial">
                </div>
            </div>
        </section>
        <div class="pos-inicio">
            <section>
                <div class="container-inicial">
                <header>
                    <nav>
                        <ul id="primeira-lista">
                            <li>
                                <img src="imagens/favicon.png" alt="Logo da loja" id="logo">
                            </li>
                            <li>
                                <form action="buscar.php" method="get">
                                    <input type="text" name="q" id="busca" placeholder="Títulos, Autores, Gêneros e Editoras">
                                    <button type="submit" id="btn-lupa">
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
                <!-- Seção INÍCIO -->
                <div id="div1" class="nova-livros-container">
                        <?php
                        $stmt = $conexao->prepare("SELECT * FROM produto");
                        $stmt->execute();
                        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($produtos as $produto) {
                            $preco_creditos = $produto['preco'] * 15;
                            echo '<div class="novo-livro">';
                            echo '<a href="livro.php?id=' . $produto['idproduto'] . '">';
                            echo '<img src="imagens-produto/' . htmlspecialchars($produto['imagem']) . '.png" alt="' . htmlspecialchars($produto['nome']) . '">';
                            echo '<h3>' . htmlspecialchars($produto['nome']) . '</h3>';
                            echo '<p>' . htmlspecialchars($produto['descricao']) . '</p>';
                            echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
                            echo '<p>Preço em Créditos: ' . $preco_creditos . ' créditos</p>';
                            echo '</a>';
                            echo '</div>';
                        }
                        ?>
                </div>
                <!-- Seção LANÇAMENTOS -->
                <div id="div2" class="nova-livros-container">
    <?php
    $stmt = $conexao->prepare("
        SELECT * FROM produto 
        WHERE YEAR(data_lancamento) = 2024
    ");
    $stmt->execute();
    $lancamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($lancamentos as $produto) {
        $preco_creditos = $produto['preco'] * 15;
        echo '<div class="novo-livro">';
        echo '<a href="livro.php?id=' . $produto['idproduto'] . '">';
        echo '<img src="imagens-produto/' . htmlspecialchars($produto['imagem']) . '.png" alt="' . htmlspecialchars($produto['nome']) . '">';
        echo '<h3>' . htmlspecialchars($produto['nome']) . '</h3>';
        echo '<p>' . htmlspecialchars($produto['descricao']) . '</p>';
        echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
        echo '<p>Preço em Créditos: ' . $preco_creditos . ' créditos</p>';
        echo '</a>';
        echo '</div>';
    }
    ?>
</div>
                <div id="div3" class="nova-livros-container">
                    <?php
                    $stmt = $conexao->prepare("
                        SELECT p.*, COUNT(vp.produto_idproduto) AS vendas
                        FROM produto p
                        JOIN venda_produto vp ON p.idproduto = vp.produto_idproduto
                        GROUP BY p.idproduto
                        ORDER BY vendas DESC
                        LIMIT 10
                    ");
                    $stmt->execute();
                    $best_sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($best_sellers as $produto) {
                        $preco_creditos = $produto['preco'] * 15;
                        echo '<div class="novo-livro">';
                        echo '<a href="livro.php?id=' . $produto['idproduto'] . '">';
                        echo '<img src="imagens-produto/' . htmlspecialchars($produto['imagem']) . '.png" alt="' . htmlspecialchars($produto['nome']) . '">';
                        echo '<h3>' . htmlspecialchars($produto['nome']) . '</h3>';
                        echo '<p>' . htmlspecialchars($produto['descricao']) . '</p>';
                        echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
                        echo '<p>Preço em Créditos: ' . $preco_creditos . ' créditos</p>';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <div id="div4" class="nova-livros-container">
    <?php
    $stmt = $conexao->prepare("
        SELECT * FROM produto 
        WHERE data_lancamento > '2024-12-01'
    ");
    $stmt->execute();
    $pre_vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($pre_vendas as $produto) {
        $preco_creditos = $produto['preco'] * 15;
        echo '<div class="novo-livro">';
        echo '<a href="livro.php?id=' . $produto['idproduto'] . '">';
        echo '<img src="imagens-produto/' . htmlspecialchars($produto['imagem']) . '.png" alt="' . htmlspecialchars($produto['nome']) . '">';
        echo '<h3>' . htmlspecialchars($produto['nome']) . '</h3>';
        echo '<p>' . htmlspecialchars($produto['descricao']) . '</p>';
        echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
        echo '<p>Preço em Créditos: ' . $preco_creditos . ' créditos</p>';
        echo '</a>';
        echo '</div>';
    }
    ?>
</div>
                <div id="div5" class="nova-autores-container">
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
                <div id="div6" class="nova-editoras-container">
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
                <div id="div7" class="nova-livros-container">
                        <?php
                        $stmt = $conexao->prepare("SELECT * FROM produto WHERE preco < 40");
                        $stmt->execute();
                        $promocoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($promocoes) {
                            foreach ($promocoes as $produto) {
                                $preco_creditos = $produto['preco'] * 15;
                                echo '<div class="novo-livro">';
                                echo '<a href="livro.php?id=' . $produto['idproduto'] . '">';
                                echo '<img src="imagens-produto/' . htmlspecialchars($produto['imagem']) . '.png" alt="' . htmlspecialchars($produto['nome']) . '">';
                                echo '<h3>' . htmlspecialchars($produto['nome']) . '</h3>';
                                echo '<p>' . htmlspecialchars($produto['descricao']) . '</p>';
                                echo '<p>De: <span class="preco-antigo">R$ ' . number_format($produto['preco'] * 1.2, 2, ',', '.') . '</span></p>';
                                echo '<p>Por: <span class="preco-promocao">R$ ' . number_format($produto['preco'], 2, ',', '.') . '</span></p>';
                                echo '<p>Preço em Créditos: ' . $preco_creditos . ' créditos</p>';
                                echo '</a>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Não há promoções disponíveis no momento.</p>';
                        }
                        ?>
                </div>
<div id="div8">
    <?php
    if (isset($_SESSION['nome']) && $_SESSION['tipo_usuario'] == 'cliente') {
        ?>
        <h1>Jogue para ganhar</h1>
        <img src="imagens-jogo/dindin.png" alt="dindin">
        <a href="indexJogo.html">
            <button>Jogue para ganhar créditos</button>
        </a>
        <?php
    } elseif (isset($_SESSION['nome'])) {
        ?>
        <h1>Conecte-se para ganhar créditos</h1>
        <img src="imagens-jogo/favicon.png" alt="azul" id="azul">
        <a href="login.php">
            <button>Faça login</button>
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
        </div>
    </div>
</body>
</html>