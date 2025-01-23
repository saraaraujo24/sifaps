<?php
session_start();
// excluir_comentario.php
include("../../api/conn.php");
$pdo = db_connect();

$usuarioLogado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

if ($usuarioLogado) {
    $comentarioId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT usuario_id FROM comentarios WHERE id = ?");
    $stmt->execute([$comentarioId]);
    $comentario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($comentario && $comentario['usuario_id'] == $usuarioLogado['id']) {
        // O usuário logado é o autor do comentário, então pode excluir
        $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id = ?");
        $stmt->execute([$comentarioId]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Você não tem permissão para excluir este comentário.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
}
?>
