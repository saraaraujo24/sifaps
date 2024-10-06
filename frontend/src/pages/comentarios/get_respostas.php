<?php
include 'db.php';

if (isset($_GET['comentario_id'])) {
    $comentario_id = $_GET['comentario_id'];
    $stmt = $pdo->prepare("SELECT * FROM comentarios WHERE resposta_id = :comentario_id ORDER BY data_criacao ASC");
    $stmt->execute(['comentario_id' => $comentario_id]);
    $respostas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($respostas);
}
?>
