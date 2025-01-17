<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit();
}

$idusuario = $_SESSION['id'];
$data = json_decode(file_get_contents('php://input'), true);
$creditosRecebidos = isset($data['creditos']) ? intval($data['creditos']) : 0;

try {
    $stmt = $conexao->prepare("SELECT creditos FROM creditos WHERE idusuario = ?");
    $stmt->execute([$idusuario]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $creditosAtuais = $result['creditos'] + $creditosRecebidos;
        $stmt = $conexao->prepare("UPDATE creditos SET creditos = ? WHERE idusuario = ?");
        $stmt->execute([$creditosAtuais, $idusuario]);
    } else {
        $creditosAtuais = $creditosRecebidos;
        $stmt = $conexao->prepare("INSERT INTO creditos (idusuario, creditos) VALUES (?, ?)");
        $stmt->execute([$idusuario, $creditosAtuais]);
    }

    echo json_encode(['success' => true, 'creditos' => $creditosAtuais]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>