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
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    if ($tipo_usuario == 'cliente') {
        $stmt = $conexao->prepare("UPDATE usuario_contato SET email = ?, telefone = ? WHERE usuario_idusuario = ?");
        $stmt->execute([$email, $telefone, $id]);
    } else if ($tipo_usuario == 'associado') {
        $stmt = $conexao->prepare("UPDATE associado_contato SET email = ?, telefone = ? WHERE associado_idassociado = ?");
        $stmt->execute([$email, $telefone, $id]);
    }

    header('Location: perfil.php');
    exit();
}
?>