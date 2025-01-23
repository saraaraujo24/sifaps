<?php
include("../../api/conn.php");

header('Content-Type: application/json');

$PDO = db_connect();
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "ID do usuário inválido."]);
        exit;
    }


    $sql = "SELECT id, nome, email, celular, cpf, cidade, estado, crp_crm, profissao, tipo_usuario,senha,nome_usuario FROM usuarios WHERE id = ?";

    $stmt = $PDO->prepare($sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Erro ao preparar a consulta."]);
        exit;
    }

    try {
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            echo json_encode(["status" => "success", "data" => $usuario]);
        } else {
            echo json_encode(["status" => "error", "message" => "Usuário não encontrado."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Erro na consulta: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID do usuário não fornecido."]);
}
?>
