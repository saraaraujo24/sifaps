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
?>
