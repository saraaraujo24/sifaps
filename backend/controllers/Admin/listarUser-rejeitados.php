<?php
include("../../api/conn.php");

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? $data['id'] : null;

$PDO = db_connect();
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

// Alterar a consulta para buscar apenas cadastros pendentes
$sql = "SELECT id, nome, email, celular, cpf, cidade, estado, data_cadastro ,status
        FROM usuarios 
        WHERE tipo_usuario = 'usuario' 
        AND status IN ('rejeitado')";
$result = $PDO->query($sql);

if ($result) {
    $usuarios = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $usuarios[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $usuarios]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao buscar dados."]);
}
?>

