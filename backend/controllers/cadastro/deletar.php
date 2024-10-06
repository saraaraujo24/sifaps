<?php
include("../../api/conn.php");

$data = json_decode(file_get_contents("php://input"), true);

/*Extrai o valor de idProfissional do array $data. Se idProfissional não 
estiver definido, ele é definido como null. O operador ??
 é conhecido como "null coalescing operator" e fornece um valor padrão se a chave não existir.*/
$idProfissional = $data['idProfissional'] ?? null;

if (empty($idProfissional)) {
    echo json_encode(['status' => 'error', 'message' => 'idProfissional não informado']);
    exit;
}

$PDO = db_connect();
$sql = "DELETE FROM cadastroProf WHERE idProfissional = :idProfissional";
$stmt = $PDO->prepare($sql);
$stmt->bindParam(':idProfissional', $idProfissional, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Usuário deletado com sucesso']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao deletar o usuário']);
}
?>
