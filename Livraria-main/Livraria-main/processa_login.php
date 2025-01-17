<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario'];

    try {
        if ($tipo_usuario === 'cliente') {
            $stmt = $conexao->prepare("
                SELECT u.idusuario, u.nome, u.senha 
                FROM usuario u
                INNER JOIN usuario_contato uc ON u.idusuario = uc.usuario_idusuario
                WHERE uc.email = :email
            ");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['id'] = $usuario['idusuario'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['tipo_usuario'] = 'cliente';
                header("Location: perfil.php");
                exit();
            } else {
                $_SESSION['erro'] = "Email ou senha incorretos.";
                header("Location: login.php");
                exit();
            }
        } elseif ($tipo_usuario === 'associado') {
            $stmt = $conexao->prepare("
                SELECT a.idassociado, a.nome, a.senha, a.cargo, d.nome AS departamento
                FROM associado a
                INNER JOIN associado_contato ac ON a.idassociado = ac.associado_idassociado
                INNER JOIN departamento d ON a.departamento_iddepartamento = d.iddepartamento
                WHERE ac.email = :email
            ");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $associado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($associado && password_verify($senha, $associado['senha'])) {
                $_SESSION['id'] = $associado['idassociado'];
                $_SESSION['nome'] = $associado['nome'];
                $_SESSION['cargo'] = $associado['cargo'];
                $_SESSION['departamento'] = $associado['departamento'];
                $_SESSION['tipo_usuario'] = 'associado';
                header("Location: perfil.php");
                exit();
            } else {
                $_SESSION['erro'] = "Email ou senha incorretos.";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['erro'] = "Tipo de usuário inválido.";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao realizar login: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }
}
?>