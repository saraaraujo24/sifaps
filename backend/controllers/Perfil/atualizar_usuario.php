<?php
session_start();
include("../../api/conn.php"); // Inclua aqui seu arquivo de conexão com o banco de dados

$conn = db_connect();

if (!$conn) {
    echo json_encode(['message' => 'Erro ao conectar ao banco de dados.']);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);



    $id = $data['id'];
    $nome = $data['nome'];
    $nome_usuario = $data['nome_usuario'];
    $email = $data['email'];
    $celular = $data['celular'];
    $cpf = $data['cpf'];
    $estado = $data['estado'];
    $cidade = $data['cidade'];
    $crp_crm = $data['crp_crm'];
    $profissao = $data['profissao'];
    $senha = $data['senha'] ?? null; // Pode ser nulo se não estiver alterando a senha

 
       // Construção dinâmica da consulta SQL
    $sql = "UPDATE usuarios SET nome = ?, nome_usuario = ?, email = ?, celular = ?, cpf = ?, estado = ?, cidade = ?, crp_crm = ?, profissao = ?";
    $params = [$nome, $nome_usuario, $email, $celular, $cpf, $estado, $cidade, $crp_crm, $profissao];

     // Adiciona o parâmetro 'senha' se ele existir
     if (isset($data['senha'])) {
        $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);
        $sql .= ", senha = ?";
        $params[] = $senhaHash;
    }

    // Adiciona a cláusula WHERE no final para garantir a ordem correta
    $sql .= " WHERE id = ?";
    $params[] = $id;

    $stmt = $conn->prepare($sql);
    if ($stmt->execute($params)) {
        echo json_encode(['message' => 'Dados atualizados com sucesso!']);
    } else {
        echo json_encode(['message' => 'Erro ao atualizar dados: ' . $stmt->errorInfo()[2]]);
    }
} else {
    echo json_encode(['message' => 'Método não permitido.']);
}

?>