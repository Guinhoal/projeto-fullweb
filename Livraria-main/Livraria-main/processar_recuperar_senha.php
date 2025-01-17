<?php
require 'conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_usuario = $_POST['tipo_usuario'];
    $nome = $_POST['nome'];
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);
    $senha = $_POST['senha'];

    $erros = [];

    if (strlen($cpf) !== 11) {
        $erros[] = "CPF inválido";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email inválido";
    }

    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
        $erros[] = "Telefone inválido";
    }

    if (strlen($senha) < 6) {
        $erros[] = "A senha deve ter no mínimo 6 caracteres";
    }

    if ($tipo_usuario === 'associado') {
        $codigo_associado = $_POST['codigo_associado'];
        if (empty($codigo_associado)) {
            $erros[] = "Código do associado é obrigatório";
        }
    }

    try {
        if ($tipo_usuario === 'cliente') {
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE cpf = :cpf");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "CPF já cadastrado";
            }

            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario_contato WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "Email já cadastrado";
            }
        } else if ($tipo_usuario === 'associado') {
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM associado WHERE cpf = :cpf");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "CPF já cadastrado";
            }

            $stmt = $conexao->prepare("SELECT COUNT(*) FROM associado_contato WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "Email já cadastrado";
            }
        }

        if (empty($erros)) {
            $conexao->beginTransaction();

            if ($tipo_usuario === 'cliente') {
                $stmt = $conexao->prepare("INSERT INTO usuario (nome, cpf, senha) VALUES (:nome, :cpf, :senha)");
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':senha', $senha_hash);
                $stmt->execute();

                $usuario_id = $conexao->lastInsertId();

                $stmt = $conexao->prepare("INSERT INTO usuario_contato (email, telefone, usuario_idusuario) VALUES (:email, :telefone, :usuario_idusuario)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':usuario_idusuario', $usuario_id);
                $stmt->execute();

                $conexao->commit();

                $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
                header("Location: login.php");
                exit();
            } else if ($tipo_usuario === 'associado') {
                switch ($codigo_associado) {
                    case 'fantinifuncionario':
                        $iddepartamento = 2; 
                        $cargo = 'Funcionário';
                        break;
                    case 'fantinigerencia':
                        $iddepartamento = 1; 
                        $cargo = 'Gerente';
                        break;
                    case 'fantiniadministracao':
                        $iddepartamento = 3; 
                        $cargo = 'Administrador';
                        break;
                    default:
                        $erros[] = "Código de associado inválido.";
                }

                if (empty($erros)) {
                    $stmt = $conexao->prepare("INSERT INTO associado (cpf, cargo, nome, senha, departamento_iddepartamento) VALUES (:cpf, :cargo, :nome, :senha, :departamento_id)");
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt->bindParam(':cpf', $cpf);
                    $stmt->bindParam(':cargo', $cargo);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':senha', $senha_hash);
                    $stmt->bindParam(':departamento_id', $iddepartamento);
                    $stmt->execute();

                    $associado_id = $conexao->lastInsertId();

                    $stmt = $conexao->prepare("INSERT INTO associado_contato (email, telefone, associado_idassociado) VALUES (:email, :telefone, :associado_id)");
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':telefone', $telefone);
                    $stmt->bindParam(':associado_id', $associado_id);
                    $stmt->execute();

                    $conexao->commit();

                    $_SESSION['mensagem'] = "Cadastro de associado realizado com sucesso!";
                    header("Location: login.php");
                    exit();
                } else {
                    $conexao->rollBack();
                    $_SESSION['erros'] = $erros;
                    header("Location: cadastro.php");
                    exit();
                }
            }
        } else {
            $_SESSION['erros'] = $erros;
            header("Location: cadastro.php");
            exit();
        }
    } catch (PDOException $e) {
        $conexao->rollBack();
        $_SESSION['erro'] = "Erro ao realizar cadastro: " . $e->getMessage();
        header("Location: cadastro.php");
        exit();
    }
}
?>