<?php
session_start();
include 'db.php';



// Verifique se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensagem_erro'] = 'Você precisa estar logado para comentar.';
    header('Location: index.php'); // Redireciona para a página de comentários
    exit();
}

// Obtenha o status do usuário logado
$usuario = $_SESSION['usuario'];

// Adicionar comentário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    // Só permita adicionar comentário se o status for aprovado
    if ($usuario['status'] === 'pendente' || $usuario['status'] === 'negado') {
        // Envie uma resposta JSON ao invés de redirecionar
        echo json_encode(['erro' => 'Você não pode adicionar comentários enquanto seu status for ' . $usuario['status'] . '.']);
        exit();
    }

    // Adiciona o comentário ao banco de dados
    $comentario = $_POST['comentario'];
    $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, usuario_id) VALUES (:comentario, :usuario_id)");
    $stmt->execute(['comentario' => $comentario, 'usuario_id' => $usuario['id']]);

    // Retorna sucesso como JSON
    echo json_encode(['sucesso' => 'Comentário adicionado com sucesso!']);
    exit();
}
// Adicionar resposta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resposta'])) {
  // Só permita adicionar comentário se o status for aprovado
  if ($usuario['status'] === 'pendente' || $usuario['status'] === 'negado') {
    // Envie uma resposta JSON ao invés de redirecionar
    echo json_encode(['erro' => 'Você não pode adicionar comentários enquanto seu status for ' . $usuario['status'] . '.']);
    exit();
}

    $resposta = $_POST['resposta'];
    $resposta_id = $_POST['resposta_id'];
    $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, resposta_id, usuario_id) VALUES (:comentario, :resposta_id, :usuario_id)");
    $stmt->execute(['comentario' => $resposta, 'resposta_id' => $resposta_id, 'usuario_id' => $usuario['id']]);
    header('Location: index.php');
    exit();
}

?>
<?php
/*include 'db.php';

// Adicionar comentário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentario = $_POST['comentario'];
    $stmt = $pdo->prepare("INSERT INTO comentarios (comentario) VALUES (:comentario)");
    $stmt->execute(['comentario' => $comentario]);
    header('Location: index.php');
    exit();
}

// Adicionar resposta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resposta'])) {
    $resposta = $_POST['resposta'];
    $resposta_id = $_POST['resposta_id'];
    $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, resposta_id) VALUES (:comentario, :resposta_id)");
    $stmt->execute(['comentario' => $resposta, 'resposta_id' => $resposta_id]);
    header('Location: index.php');
    exit();
}*/
?>