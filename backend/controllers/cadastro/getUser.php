<?php

// Inclui o arquivo que contém a função de conexão com o banco
require_once '../../api/conn.php'; // Altere o caminho conforme necessário

// Verifica se o parâmetro 'idProfissional' foi passado na URL
if (isset($_GET['idUser'])) {
    $idUser = $_GET['idUser'];

    try {
        // Conecta ao banco de dados usando a função db_connect
        $pdo = db_connect();

        // Query para buscar o cadastro pelo ID
        $sql = "SELECT * FROM cadastroUser WHERE idUser = :idUser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->execute();

        // Obtém o resultado
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário foi encontrado
        if ($usuario) {
            echo json_encode(['status' => 'success', 'data' => $usuario]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuário não encontrado']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar dados: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID não informado']);
}
?>


