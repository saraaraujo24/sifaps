<?php
include("../../api/conn.php");

header('Content-Type: application/json');

$PDO = db_connect();
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

// Verifica se o ID do usuário e o novo status foram enviados
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Verifica se o status é válido
    if (!in_array($status, ['aprovado', 'rejeitado', 'pendente'])) {
        echo json_encode(["status" => "error", "message" => "Status inválido."]);
        exit;
    }

    // Atualiza o status do usuário
    $sql = "UPDATE usuarios SET status = ? WHERE id = ?";
    $stmt = $PDO->prepare($sql);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Status atualizado com sucesso."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao atualizar status."]);
    }

 
} else {
    echo json_encode(["status" => "error", "message" => "ID ou status não fornecidos."]);
}

$PDO = null;
?>
