<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['tipo_usuario'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    $cpf_ant = $_POST['cpf_ant'];

    try {
        $conexao->beginTransaction();


        if ($tipo_usuario == 'associado') {
            if (!empty($senha)) {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $conexao->prepare("UPDATE associado SET nome = ?, cpf = ?, senha = ? WHERE idassociado = ?");
                $stmt->execute([$nome, $cpf, $senha_hash, $id]);
        
                $stmt = $conexao->prepare("
                    UPDATE usuario u
                    SET u.nome = ?, u.cpf = ?, u.senha = ?
                    WHERE u.cpf = (SELECT a.cpf FROM associado a WHERE a.idassociado = ?)
                ");
                $stmt->execute([$nome, $cpf, $senha_hash, $id]);
            } else {
                $stmt = $conexao->prepare("UPDATE associado SET nome = ?, cpf = ? WHERE idassociado = ?");
                $stmt->execute([$nome, $cpf, $id]);
        
                $stmt = $conexao->prepare("
                    UPDATE usuario u
                    SET u.nome = ?, u.cpf = ?
                    WHERE u.cpf = (SELECT a.cpf FROM associado a WHERE a.idassociado = ?)
                ");
                $stmt->execute([$nome, $cpf, $id]);
            } } else {
                if (!empty($senha)) {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, cpf = ?, senha = ? WHERE idusuario = ?");
                $stmt->execute([$nome, $cpf, $senha_hash, $id]);
                } else {
                $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, cpf = ? WHERE idusuario = ?");
                $stmt->execute([$nome, $cpf, $id]);
                }
         }


        $_SESSION['nome'] = $nome;
        $conexao->commit();
        header('Location: perfil.php');
        exit();
    } catch (Exception $e) {
        $conexao->rollBack();
        $_SESSION['erro'] = "Erro ao atualizar dados: " . $e->getMessage();
        header('Location: dados_pessoais.php');
        exit();
    }
}
?>