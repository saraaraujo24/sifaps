<?php
$host = 'localhost';
$db = 'sifaps'; // Substitua pelo seu nome de banco de dados
$user = 'root'; // Substitua pelo seu usuário do MySQL
$pass = ''; // Substitua pela sua senha do MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Conexão falhou: ' . $e->getMessage();
    exit(); // Adicione um exit() para evitar continuar a execução se a conexão falhar
}

/*include 'db.php';
header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se é um comentário ou uma resposta
    if (isset($_POST['comentario']) && !empty($_POST['comentario']) && isset($_POST['idUser']) && !empty($_POST['idUser'])) {
        // Lógica para adicionar comentário
        $comentario = $_POST['comentario'];
        $idUser = $_POST['idUser'];

        // Inserir comentário
        $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, resposta_id, usuario_id, data_criacao) VALUES (:comentario, NULL, :usuario_id, NOW())");
        $stmt->execute([
            ':comentario' => $comentario,
            ':usuario_id' => $idUser
        ]);

        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Comentário adicionado com sucesso!']);
    } 
    // Caso seja uma resposta a outro comentário
    else if (isset($_POST['resposta']) && !empty($_POST['resposta']) && isset($_POST['resposta_id']) && !empty($_POST['resposta_id']) && isset($_POST['idUser']) && !empty($_POST['idUser'])) {
        // Lógica para adicionar resposta
        $resposta = $_POST['resposta'];
        $resposta_id = $_POST['resposta_id'];
        $idUser = $_POST['idUser'];

        // Inserir resposta
        $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, resposta_id, usuario_id, data_criacao) VALUES (:comentario, :resposta_id, :usuario_id, NOW())");
        $stmt->execute([
            ':comentario' => $resposta,
            ':resposta_id' => $resposta_id,
            ':usuario_id' => $idUser
        ]);

        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Resposta adicionada com sucesso!']);
    } 
    // Caso não seja fornecido comentário, resposta ou ID do usuário
    else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro: comentário, resposta ou ID do usuário inválido.']);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido.']);
}*/
?>