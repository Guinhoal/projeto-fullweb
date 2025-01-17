<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'associado') {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];
$cargo = $_SESSION['cargo'];

function listarFuncionarios($conexao) {
    $stmt = $conexao->prepare("SELECT * FROM associado WHERE cargo = 'Funcionário'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function listarClientes($conexao) {
    $stmt = $conexao->prepare("SELECT * FROM usuario WHERE is_associado = 0");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function listarProdutos($conexao) {
    $stmt = $conexao->prepare("SELECT p.*, a.nome AS autor, e.nome AS editora
                               FROM produto p
                               JOIN autor a ON p.idautor = a.idautor
                               JOIN editora e ON p.ideditora = e.ideditora");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['excluir_funcionario_id']) && $cargo === 'Gerente') {
        $funcionario_id = $_POST['excluir_funcionario_id'];
        $stmt = $conexao->prepare("DELETE FROM associado WHERE idassociado = ?");
        $stmt->execute([$funcionario_id]);
    } elseif (isset($_POST['excluir_cliente_id']) && $cargo === 'Administrador') {
        $cliente_id = $_POST['excluir_cliente_id'];
        $stmt = $conexao->prepare("DELETE FROM usuario WHERE idusuario = ?");
        $stmt->execute([$cliente_id]);
    } elseif (isset($_POST['excluir_produto_id'])) {
        $produto_id = $_POST['excluir_produto_id'];
        $stmt = $conexao->prepare("DELETE FROM produto WHERE idproduto = ?");
        $stmt->execute([$produto_id]);
    } elseif (isset($_POST['editar_produto_id'])) {
        $produto_id = $_POST['editar_produto_id'];
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $stmt = $conexao->prepare("UPDATE produto SET nome = ?, descricao = ?, preco = ? WHERE idproduto = ?");
        $stmt->execute([$nome, $descricao, $preco, $produto_id]);
    }
}

$funcionarios = $cargo === 'Gerente' || $cargo === 'Administrador' ? listarFuncionarios($conexao) : [];
$clientes = $cargo === 'Administrador' ? listarClientes($conexao) : [];
$produtos = listarProdutos($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar - GhostBooks</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .gerenciar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            margin-top: 40px;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img {
            max-width: 50px;
        }
        .form-inline {
            display: flex;
            flex-direction: column;
        }
        .form-inline input[type="text"],
        .form-inline input[type="number"] {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-inline button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-inline button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="voltar-inicio">
        <a href="index.php">Início</a>
    </div>
    <main class="gerenciar-container">
        <?php if ($cargo === 'Gerente' || $cargo === 'Administrador') { ?>
            <h2>Gerenciar Funcionários</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($funcionarios as $funcionario) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($funcionario['idassociado']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['nome']); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="excluir_funcionario_id" value="<?php echo htmlspecialchars($funcionario['idassociado']); ?>">
                                    <button type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

        <?php if ($cargo === 'Administrador') { ?>
            <h2>Gerenciar Clientes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['idusuario']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="excluir_cliente_id" value="<?php echo htmlspecialchars($cliente['idusuario']); ?>">
                                    <button type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

        <h2>Gerenciar Produtos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Autor</th>
                    <th>Editora</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $index => $produto) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($produto['idproduto']); ?></td>
                        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                        <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                        <td><?php echo htmlspecialchars($produto['preco']); ?></td>
                        <td><?php echo htmlspecialchars($produto['autor']); ?></td>
                        <td><?php echo htmlspecialchars($produto['editora']); ?></td>
                        <td><img src="imagens/livro<?php echo $index + 1; ?>.jpg" alt="Imagem do Produto"></td>
                        <td>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="excluir_produto_id" value="<?php echo htmlspecialchars($produto['idproduto']); ?>">
                                <button type="submit">Excluir</button>
                            </form>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="editar_produto_id" value="<?php echo htmlspecialchars($produto['idproduto']); ?>">
                                <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
                                <input type="text" name="descricao" value="<?php echo htmlspecialchars($produto['descricao']); ?>" required>
                                <input type="number" step="0.01" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
                                <button type="submit">Editar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>