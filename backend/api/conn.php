<?php
// api/login/conn.php
//conexão com o banco 
function db_connect() {
    $hostname = "localhost";
    $user = "root";
    $password = "";
    $bd = "sifaps";

    try {
        $pdo = new PDO("mysql:host=$hostname;dbname=$bd", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die(json_encode(['status' => 'error', 'message' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]));
        exit;
    }
}

?>



