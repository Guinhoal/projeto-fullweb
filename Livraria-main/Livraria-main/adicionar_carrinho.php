<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    echo "<script>alert('Por favor, faça login para continuar.'); window.location.href='login.php';</script>";
    exit();
}

$idusuario = $_SESSION['id'];

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$idproduto = intval($_GET['id']);
$quantidade = 1; 

$stmt = $conexao->prepare("SELECT quantidade FROM estoque WHERE produto_idproduto = :idproduto");
$stmt->bindParam(':idproduto', $idproduto, PDO::PARAM_INT);
$stmt->execute();
$estoque = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$estoque || $estoque['quantidade'] <= 0) {
    echo "<script>alert('Produto esgotado.'); window.location.href='livro.php?id=$idproduto';</script>";
    exit();
}

$stmt = $conexao->prepare("SELECT cp.*, c.idcarrinho FROM carrinho_produto cp JOIN carrinho c ON cp.carrinho_idcarrinho = c.idcarrinho WHERE c.usuario_idusuario = :idusuario AND cp.produto_idproduto = :idproduto");
$stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$stmt->bindParam(':idproduto', $idproduto, PDO::PARAM_INT);
$stmt->execute();
$carrinho_produto = $stmt->fetch(PDO::FETCH_ASSOC);

if ($carrinho_produto) {
    $nova_quantidade = $carrinho_produto['quantidade'] + $quantidade;
    if ($nova_quantidade > $estoque['quantidade']) {
        echo "<script>alert('A quantidade solicitada excede o estoque disponível.'); window.location.href='carrinho.php';</script>";
        exit();
    }
    $stmt = $conexao->prepare("UPDATE carrinho_produto SET quantidade = :quantidade WHERE idcarrinho_produto = :idcarrinho_produto");
    $stmt->bindParam(':quantidade', $nova_quantidade, PDO::PARAM_INT);
    $stmt->bindParam(':idcarrinho_produto', $carrinho_produto['idcarrinho_produto'], PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $conexao->prepare("SELECT idcarrinho FROM carrinho WHERE usuario_idusuario = :idusuario");
    $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
    $stmt->execute();
    $carrinho = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carrinho) {
        $stmt = $conexao->prepare("INSERT INTO carrinho (usuario_idusuario) VALUES (:idusuario)");
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->execute();
        $idcarrinho = $conexao->lastInsertId();
    } else {
        $idcarrinho = $carrinho['idcarrinho'];
    }

    $stmt = $conexao->prepare("INSERT INTO carrinho_produto (carrinho_idcarrinho, produto_idproduto, quantidade, preco) VALUES (:idcarrinho, :idproduto, :quantidade, (SELECT preco FROM produto WHERE idproduto = :idproduto))");
    $stmt->bindParam(':idcarrinho', $idcarrinho, PDO::PARAM_INT);
    $stmt->bindParam(':idproduto', $idproduto, PDO::PARAM_INT);
    $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
    $stmt->execute();
}

header('Location: carrinho.php');
exit();
?>