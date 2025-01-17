<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_SESSION['reset_token']) && 
    isset($_POST['token']) && 
    $_SESSION['reset_token'] === $_POST['token']) {
    
    $nova_senha = $_POST['nova_senha'];
    $usuario_id = $_SESSION['reset_id'];
    
    try {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $stmt = $conexao->prepare("UPDATE usuario SET senha = :senha WHERE idusuario = :id");
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id', $usuario_id);
        $stmt->execute();
        
        // Limpar dados de recuperação
        unset($_SESSION['reset_token']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_id']);
        
        $_SESSION['mensagem'] = "Senha alterada com sucesso!";
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao atualizar senha: " . $e->getMessage();
        header("Location: recuperar_senha.php");
        exit();
    }
} else {
    header("Location: recuperar_senha.php");
    exit();
}
?>