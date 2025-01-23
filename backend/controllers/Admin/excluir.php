<?php
include("../../api/conn.php"); // Inclua o arquivo da função de conexão

// Chame a função db_connect para obter a conexão com o banco de dados
$pdo = db_connect();

// Verifique se a variável $pdo foi definida corretamente
if (!$pdo) {
    die('Falha ao conectar ao banco de dados');
}

// Verifica se foi enviado o ID via POST
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = $data['id'];

    try {
        // Prepare a consulta para excluir o usuário com o ID fornecido
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Verifica se a exclusão foi bem-sucedida
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Usuário excluído com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nenhum usuário encontrado com o ID fornecido']);
        }
    } catch (PDOException $e) {
        // Caso ocorra um erro ao tentar excluir
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir o usuário: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID do usuário não fornecido']);
}
?>
