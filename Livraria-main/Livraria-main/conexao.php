<?php

$host = 'localhost';
$usuario = 'a2023952616@teiacoltec.org';
$senha = '@Coltec2024';
$banco = 'a2023952616@teiacoltec.org';

try {
    $dsn = "mysql:host=$host;dbname=$banco";
    
    $conexao = new PDO($dsn, $usuario, $senha);
    
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Falha na conexão: " . $e->getMessage());
}
?>

