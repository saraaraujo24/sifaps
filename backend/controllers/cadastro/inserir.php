<?php
// controllers/cadastro/inserir.php
include("../../api/conn.php");


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../api/conn.php'; // Inclui o arquivo de conexão

    // Cria a conexão com o banco de dados
    $pdo = db_connect();

    // Verifica se os dados foram enviados corretamente
    if (empty($_POST)) {
        echo json_encode(['status' => 'error', 'message' => 'Nenhum dado foi enviado']);
        exit;
    }

    // Coleta os dados enviados do formulário
    $tipo_usuario = $_POST['tipo_usuario'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha = isset($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_BCRYPT) : null; // Hasheando a senha
    $celular = $_POST['celular'] ?? null;
    $cpf = $_POST['cpf'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $crp_crm = $_POST['crp_crm'] ?? null;
    $profissao = $_POST['profissao'] ?? null;
    $nome_usuario = $_POST['nome_usuario'] ?? null;


    // Valida se os campos obrigatórios estão presentes
    if (!$tipo_usuario || !$nome || !$email || !$senha || !$celular || !$cpf || !$estado || !$cidade ) {
        echo json_encode(['status' => 'error', 'message' => 'Campos obrigatórios estão faltando']);
        exit;
    }

        // Verifica se o usuário é um profissional e ajusta os campos obrigatórios
    if ($tipo_usuario === 'profissional') {
        // Para profissionais, o campo nome_usuario não é obrigatório
    } else {
        // Para outros tipos de usuário, o campo nome_usuario é obrigatório
        if (!$nome_usuario) {
            echo json_encode(['status' => 'error', 'message' => 'O campo nome_usuario é obrigatório']);
            exit;
        }
    }

    $status = 'aprovado'; // Status padrão para usuários comuns

    if ($crp_crm && $profissao) {
        $status = 'pendente'; // Define o status como pendente para profissionais
    }


    try {
        // Prepara a consulta SQL para inserir os dados no banco
        $sql = "INSERT INTO usuarios (tipo_usuario,nome_usuario, nome, email, senha, celular, cpf, estado, cidade, crp_crm, profissao, status) 
                VALUES (:tipo_usuario,:nome_usuario, :nome, :email, :senha, :celular, :cpf, :estado, :cidade, :crp_crm, :profissao, :status)";
        
        $stmt = $pdo->prepare($sql);
        
        // Associa os valores aos parâmetros da consulta
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);
        $stmt->bindParam(':nome_usuario', $nome_usuario);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':crp_crm', $crp_crm);
        $stmt->bindParam(':profissao', $profissao);
        $stmt->bindParam(':status', $status); 

        // Executa a consulta
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Cadastro realizado com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Falha ao realizar o cadastro']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido']);
}