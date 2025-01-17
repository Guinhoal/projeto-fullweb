<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    echo "<script>alert('Por favor, faça login para continuar.'); window.location.href='login.php';</script>";
    exit();
}

$idusuario = $_SESSION['id'];
$idcarrinho_produto = intval($_GET['id']);

$stmt = $conexao->prepare("SELECT cp.idcarrinho_produto FROM carrinho_produto cp JOIN carrinho c ON cp.carrinho_idcarrinho = c.idcarrinho WHERE cp.idcarrinho_produto = :idcarrinho_produto AND c.usuario_idusuario = :idusuario");
$stmt->bindParam(':idcarrinho_produto', $idcarrinho_produto, PDO::PARAM_INT);
$stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo "<script>alert('Produto não encontrado no seu carrinho.'); window.location.href='carrinho.php';</script>";
    exit();
}

$stmt = $conexao->prepare("DELETE FROM carrinho_produto WHERE idcarrinho_produto = :idcarrinho_produto");
$stmt->bindParam(':idcarrinho_produto', $idcarrinho_produto, PDO::PARAM_INT);
$stmt->execute();

echo "<script>alert('Produto removido do carrinho com sucesso.'); window.location.href='carrinho.php';</script>";
exit();
?>