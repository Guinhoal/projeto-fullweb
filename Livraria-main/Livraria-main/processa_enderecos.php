<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['tipo_usuario'])) {
    header('Location: perfil.php');
    exit();
}

$id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$acao = $_POST['acao'];

$pais = trim($_POST['pais']);
$estado = trim($_POST['estado']);
$cidade = trim($_POST['cidade']);
$endereco = trim($_POST['endereco']);

if (empty($pais) || empty($estado) || empty($cidade) || empty($endereco)) {
    echo "Todos os campos são obrigatórios!";
    exit();
}

try {
    if ($acao == 'adicionar') {
        if ($tipo_usuario == 'cliente') {
            $stmt = $conexao->prepare("INSERT INTO endereco_usuario (pais, estado, cidade, endereco, usuario_idusuario) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$pais, $estado, $cidade, $endereco, $id]);
        } else if ($tipo_usuario == 'associado') {
            $stmt = $conexao->prepare("INSERT INTO endereco_associado (pais, estado, cidade, endereco, associado_idassociado) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$pais, $estado, $cidade, $endereco, $id]);
        }
    } else if ($acao == 'editar') {
        $idendereco = $_POST['idendereco'];
        if ($tipo_usuario == 'cliente') {
            $stmt = $conexao->prepare("UPDATE endereco_usuario SET pais = ?, estado = ?, cidade = ?, endereco = ? WHERE idendereco_usuario = ? AND usuario_idusuario = ?");
            $stmt->execute([$pais, $estado, $cidade, $endereco, $idendereco, $id]);
        } else if ($tipo_usuario == 'associado') {
            $stmt = $conexao->prepare("UPDATE endereco_associado SET pais = ?, estado = ?, cidade = ?, endereco = ? WHERE idendereco_associado = ? AND associado_idassociado = ?");
            $stmt->execute([$pais, $estado, $cidade, $endereco, $idendereco, $id]);
        }
    }

    header('Location: perfil.php');
    exit();
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>