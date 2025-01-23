<?php
include("../../api/conn.php");

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? $data['id'] : null;
$status = isset($data['status']) ? $data['status'] : null;

if (!$id || !$status) {
    echo json_encode(["status" => "error", "message" => "ID ou status não fornecido."]);
    exit;
}

$PDO = db_connect();
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

// Atualiza o status do usuário
$sql = "UPDATE usuarios SET status = :status WHERE id = :id";
$stmt = $PDO->prepare($sql);

$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Status do usuário atualizado com sucesso."]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao atualizar o status do usuário."]);
}
?>
