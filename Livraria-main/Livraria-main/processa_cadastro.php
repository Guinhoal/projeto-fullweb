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

    // Validações
    $erros = [];

    // Validar CPF
    if (strlen($cpf) !== 11) {
        $erros[] = "CPF inválido";
    }

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email inválido";
    }

    // Validar telefone
    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
        $erros[] = "Telefone inválido";
    }

    // Validar senha
    if (strlen($senha) < 6) {
        $erros[] = "A senha deve ter no mínimo 6 caracteres";
    }

    try {
        if ($tipo_usuario === 'cliente') {
            // Verificar se CPF já existe em usuário
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE cpf = :cpf");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "CPF já cadastrado como cliente";
            }

            // Verificar se email já existe em usuário_contato
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario_contato WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "Email já cadastrado como cliente";
            }

            // Verificar se CPF já existe em associado
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM associado WHERE cpf = :cpf");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "CPF já cadastrado como associado";
            }

            // Verificar se email já existe em associado_contato
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM associado_contato WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "Email já cadastrado como associado";
            }

            if (empty($erros)) {
                $conexao->beginTransaction();

                // Inserir na tabela usuário
                $stmt = $conexao->prepare("INSERT INTO usuario (nome, cpf, senha, is_associado) VALUES (:nome, :cpf, :senha, 0)");
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':senha', $senha_hash);
                $stmt->execute();

                // Obter o ID do usuário inserido
                $usuario_id = $conexao->lastInsertId();

                // Inserir na tabela usuario_contato
                $stmt = $conexao->prepare("INSERT INTO usuario_contato (email, telefone, usuario_idusuario) VALUES (:email, :telefone, :usuario_idusuario)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':usuario_idusuario', $usuario_id);
                $stmt->execute();

                $conexao->commit();

                $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['erros'] = $erros;
                header("Location: cadastro.php");
                exit();
            }
        } elseif ($tipo_usuario === 'associado') {
            // Verificar o código e definir o departamento
            $codigo_associado = $_POST['codigo_associado'];
            if (empty($codigo_associado)) {
                $erros[] = "Código do associado é obrigatório";
            }

            switch ($codigo_associado) {
                case 'fantinifuncionario':
                    $iddepartamento = 2; // Vendas
                    $cargo = 'Funcionário';
                    break;
                case 'fantinigerencia':
                    $iddepartamento = 1; // Gerencia
                    $cargo = 'Gerente';
                    break;
                case 'fantiniadministracao':
                    $iddepartamento = 3; // Administração
                    $cargo = 'Administrador';
                    break;
                default:
                    $erros[] = "Código de associado inválido.";
            }

            // Verificar se CPF já existe em associado
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM associado WHERE cpf = :cpf");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "CPF já cadastrado como associado";
            }

            // Verificar se email já existe em associado_contato
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM associado_contato WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "Email já cadastrado como associado";
            }

            // Verificar se CPF já existe em usuário
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE cpf = :cpf");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "CPF já cadastrado como cliente";
            }

            // Verificar se email já existe em usuario_contato
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario_contato WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "Email já cadastrado como cliente";
            }

            if (empty($erros)) {
                $conexao->beginTransaction();

                // Inserir na tabela associado
                $stmt = $conexao->prepare("INSERT INTO associado (cpf, cargo, nome, senha, departamento_iddepartamento) VALUES (:cpf, :cargo, :nome, :senha, :departamento_id)");
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':cargo', $cargo);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':senha', $senha_hash);
                $stmt->bindParam(':departamento_id', $iddepartamento);
                $stmt->execute();

                // Obter o ID do associado inserido
                $associado_id = $conexao->lastInsertId();

                // Inserir na tabela associado_contato
                $stmt = $conexao->prepare("INSERT INTO associado_contato (email, telefone, associado_idassociado) VALUES (:email, :telefone, :associado_id)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':associado_id', $associado_id);
                $stmt->execute();

                // Inserir na tabela usuário com is_associado = 1
                $stmt = $conexao->prepare("INSERT INTO usuario (nome, cpf, senha, is_associado) VALUES (:nome, :cpf, :senha, 1)");
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':senha', $senha_hash);
                $stmt->execute();

                $conexao->commit();

                $_SESSION['mensagem'] = "Cadastro de associado realizado com sucesso!";
                header("Location: login.php");
                exit();

            } else {
                $_SESSION['erros'] = $erros;
                header("Location: cadastro.php");
                exit();
            }
        } else {
            $erros[] = "Tipo de usuário inválido.";
            $_SESSION['erros'] = $erros;
            header("Location: cadastro.php");
            exit();
        }
    } catch (PDOException $e) {
        if ($conexao->inTransaction()) {
            $conexao->rollBack();
        }
        $_SESSION['erro'] = "Erro ao realizar cadastro: " . $e->getMessage();
        header("Location: cadastro.php");
        exit();
    }
}
?>