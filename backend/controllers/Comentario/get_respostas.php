<?php
include("../../api/conn.php");
$pdo = db_connect(); // Certifique-se de usar o mesmo nome de variável aqui

if (isset($_GET['comentario_id'])) {
    $comentario_id = $_GET['comentario_id'];

    try {
        // Prepara a query para buscar respostas relacionadas ao comentário
        $stmt = $pdo->prepare("SELECT * FROM comentarios WHERE resposta_id = :comentario_id ORDER BY data_criacao DESC");
        $stmt->execute(['comentario_id' => $comentario_id]);
        $respostas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retorna as respostas como JSON
        echo json_encode($respostas);
    } catch (PDOException $e) {
        // Retorna um erro em formato JSON caso ocorra um problema
        echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar respostas: ' . $e->getMessage()]);
    }
} else {
    // Retorna erro caso o parâmetro não tenha sido enviado
    echo json_encode(['status' => 'error', 'message' => 'Parâmetro comentario_id não fornecido.']);
}
?>
