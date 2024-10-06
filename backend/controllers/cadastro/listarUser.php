<?php
include("../../api/conn.php");

header('Content-Type: application/json');

$PDO = db_connect(); // Certifique-se de que a função db_connect() está corretamente definida
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

$sql = "SELECT idUser, nome, email,celular, cpf, cidade, estado FROM cadastroUser";
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



