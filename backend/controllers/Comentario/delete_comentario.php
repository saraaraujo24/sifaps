<?php
// excluir_comentario.php
include("../../api/conn.php");
$pdo = db_connect(); // Certifique-se de usar o mesmo nome de variável aqui

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario_id = $_POST['comentario_id'];

    // Verifica se o ID foi fornecido
    if (empty($comentario_id)) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'ID do comentário não fornecido.']);
        exit;
    }

    // Exclui o comentário do banco de dados
    $query = "DELETE FROM comentarios WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $comentario_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Comentário excluído com sucesso.']);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao excluir o comentário.']);
    }
}
?>
