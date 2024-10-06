<?php
include("../../api/conn.php");

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
if ($data === null) {
    echo json_encode(["status" => "error", "message" => "Erro ao decodificar JSON."]);
    exit;
}
$id = isset($data['id']) ? $data['id'] : null; // Corrigido

if (empty($id)) {
    echo json_encode(["status" => "error", "message" => "ID não fornecido."]);
    exit;
}

$PDO = db_connect();
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

$sql = "UPDATE cadastroProf SET status = 'aprovado' WHERE idProfissional = :id";
$stmt = $PDO->prepare($sql);
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Cadastro aprovado com sucesso."]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao aprovar cadastro.", "details" => $stmt->errorInfo()]);
}
?>
