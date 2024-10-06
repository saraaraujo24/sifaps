<?php
// Inclui a conexão com o banco de dados
include("../../api/conn.php"); // Certifique-se de ajustar o caminho

header("Access-Control-Allow-Origin: *"); // Ajuste conforme necessário
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');


// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados enviados pelo formulário
    $idUser = $_POST['idUser'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $celular = $_POST['celular'];
    $cpf = $_POST['cpf'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];


    // Registra os dados recebidos no log do servidor
    error_log("Dados recebidos: ");
    error_log("ID Cadastro: " . $idUser);
    error_log("Nome: " . $nome);
    error_log("Email: " . $email);
    error_log("Senha: " . $senha);
    error_log("Celular: " . $celular);
    error_log("CPF: " . $cpf);
    error_log("Cidade: " . $cidade);
    error_log("Estado: " . $estado);


    try {
        // Conecta ao banco de dados
        $pdo = db_connect();

        // Prepara a query de atualização
        $sql = "UPDATE cadastroUser 
                SET nome = :nome, email = :email,senha = :senha, celular = :celular, cpf = :cpf, cidade = :cidade, estado = :estado
                WHERE idUser = :idUser";
        $stmt = $pdo->prepare($sql);

        // Liga os parâmetros
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);

        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_STR); // Use PDO::PARAM_STR se idUser não for um inteiro

        // Registra a consulta e os parâmetros no log
        error_log("SQL: " . $sql);
        error_log("Parâmetros: " . json_encode([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha,
            'celular' => $celular,
            'cpf' => $cpf,
            'cidade' => $cidade,
            'estado' => $estado
       
        ]));

        // Executa a query
        if ($stmt->execute()) {
            error_log("Atualização bem-sucedida");
            echo json_encode(['status' => 'success', 'message' => 'Cadastro atualizado com sucesso']);
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("Erro ao executar a query: " . print_r($errorInfo, true));
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o cadastro']);
        }
    } catch (Exception $e) {
        error_log("Erro ao processar a requisição: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Erro ao processar a requisição: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido']);
}

?>


