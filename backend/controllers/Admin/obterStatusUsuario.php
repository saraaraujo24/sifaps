<?php
// Inclui a conexão com o banco de dados
include("../../api/conn.php");

$pdo = db_connect();
// Verifica se o parâmetro 'id' foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Obtenha o id do usuário a partir da URL

    // Verifique a conexão com o banco de dados
    if (!$pdo) {
        echo json_encode(['status' => 'error', 'message' => 'Erro na conexão com o banco de dados']);
        exit;
    }

    try {
        // Prepare a consulta SQL para obter o usuário com o id fornecido
        $stmt = $pdo->prepare("SELECT id, nome, email,nome_usuario, status, tipo_usuario,crp_crm FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Verifica se encontrou o usuário
        if ($stmt->rowCount() > 0) {
            // Retorna os dados do usuário em formato JSON
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'usuario' => $usuario]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuário não encontrado']);
        }
    } catch (PDOException $e) {
        // Caso ocorra um erro na execução da consulta
        echo json_encode(['status' => 'error', 'message' => 'Erro ao consultar o banco de dados: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID do usuário não fornecido']);
}
?>
