<?php
include 'db.php'; // Conexão com o banco de dados

// Verifica se a conexão foi estabelecida
if (!$conn) {
    die("Erro ao conectar ao banco de dados.");
}

// Verifica se o ID foi enviado pelo formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Garante que o ID seja um número inteiro

    // Deleta a publicação com base no ID
    $sql = "DELETE FROM publicacoes WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Publicação excluída com sucesso!";
    } else {
        echo "Erro ao excluir publicação: " . $conn->error;
    }
} else {
    echo "ID da publicação não recebido.";
}

// Fecha a conexão com o banco de dados
$conn->close();

// Redireciona de volta para a página principal (opcional)
header("Location: index.php");
exit();
?>
