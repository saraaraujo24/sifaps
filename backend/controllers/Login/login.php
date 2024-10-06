<?php
session_start();
include("../../api/conn.php");

header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = [];
$pdo = db_connect();

if (!$pdo) {
    error_log("Erro na conexão com o banco de dados.");
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST["senha"])) {
        $response = ['status' => 'error', 'message' => 'Por favor, preencha todos os campos'];
        error_log("Campos não preenchidos: " . json_encode($_POST));
        echo json_encode($response);
        exit;
    } else {
        $senha = $_POST["senha"];
        $isProfessional = !empty($_POST["crp_crm"]); // Verifica se o campo CRP/CRM foi preenchido

        if ($isProfessional) {
            $crp_crm = $_POST["crp_crm"];
            $stmt = $pdo->prepare("SELECT * FROM cadastroProf WHERE crp_crm = :crp_crm AND senha = :senha");
            error_log("Executando consulta com CRP/CRM: $crp_crm");
            $stmt->execute(['crp_crm' => $crp_crm, 'senha' => $senha]);
        } else {
            if (empty($_POST["email"])) {
                $response = ['status' => 'error', 'message' => 'Por favor, preencha todos os campos'];
                echo json_encode($response);
                exit;
            }
            $email = $_POST["email"];
            $stmt = $pdo->prepare("SELECT * FROM cadastroUser WHERE email = :email AND senha = :senha");
            error_log("Executando consulta na tabela cadastroUser, Email: $email");
            $stmt->execute(['email' => $email, 'senha' => $senha]);
        }

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            if ($isProfessional) {
                $_SESSION["crp_crm"] = $crp_crm; // Armazena CRP/CRM na sessão
            } else {
                $_SESSION["email"] = $email; // Armazena email na sessão
            }
            $_SESSION["senha"] = $row->senha; // Armazena a senha na sessão
        
            $response = [
                'status' => 'success',
                'message' => 'Login bem-sucedido!',
                'data' => $row // Inclua os dados do usuário
            ];
            error_log("Login bem-sucedido para: " . ($isProfessional ? "CRP: $crp_crm" : "Email: $email"));
        } else {
            $response = ['status' => 'error', 'message' => 'E-mail ou senha incorretos.'];
            error_log("Falha no login para: " . ($isProfessional ? "CRP: $crp_crm" : "Email: $email"));
        }
    }

    echo json_encode($response);
} else {
    error_log("Método de requisição inválido.");
}
?>


