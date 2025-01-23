<?php
$host = 'localhost';
$db = 'sifaps';
$user = 'root';  // Nome de usuário
$pass = '';      // Senha vazia

$conn = new mysqli($host, $user, $pass, $db);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
