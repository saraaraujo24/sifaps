<?php
// Incluir a conexão com o banco de dados
include("../../api/conn.php");

// Definir cabeçalhos para aceitar requisições em JSON
header('Content-Type: application/json');

// Recuperar os dados JSON enviados
$data = json_decode(file_get_contents('php://input'), true);

// Verificar se o banco de dados foi conectado com sucesso
$conn = db_connect();

// Validar se email e senha foram passados
if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    try {
        // Preparar a consulta com PDO para prevenir SQL Injection
        $sql = "SELECT * FROM admin WHERE email = :email";
        $stmt = $conn->prepare($sql);
        
        // Bind do parâmetro
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        // Executar a consulta
        $stmt->execute();
        
        // Verificar se encontrou o usuário
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar a senha utilizando password_verify (caso as senhas estejam armazenadas com hash)
            if ($user && password_verify($password, $user['senha'])) {
                // Login bem-sucedido
                echo json_encode(['success' => true]);
            } else {
                // Senha incorreta
                echo json_encode(['success' => false, 'message' => 'Credenciais inválidas.']);
            }
        } else {
            // Usuário não encontrado
            echo json_encode(['success' => false, 'message' => 'Credenciais inválidas.']);
        }
    } catch (PDOException $e) {
        // Erro na execução da consulta
        echo json_encode(['success' => false, 'message' => 'Erro na execução da consulta: ' . $e->getMessage()]);
    }
} else {
    // Dados inválidos (email ou senha ausente)
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
}

?>
