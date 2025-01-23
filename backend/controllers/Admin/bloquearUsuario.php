<?php
include("../../api/conn.php");

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? $data['id'] : null;

if (!$id) {
    echo json_encode(["status" => "error", "message" => "ID do usuário não fornecido."]);
    exit;
}

$PDO = db_connect();
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

// Atualiza o status do usuário para bloqueado
$sql = "UPDATE usuarios SET status = 'rejeitado' WHERE id = :id";
$stmt = $PDO->prepare($sql);

$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Usuário bloqueado com sucesso."]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao bloquear o usuário."]);
}
?>
