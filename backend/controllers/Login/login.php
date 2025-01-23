<?php
session_start();
include("../../api/conn.php");

header('Content-Type: application/json');

// Exibe erros para depuração
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
    // Verifica se o campo senha foi preenchido
    if (empty($_POST["senha"])) {
        $response = ['status' => 'error', 'message' => 'O campo senha é obrigatório'];
        echo json_encode($response);
        exit;
    }

    $senha = $_POST["senha"];
    $isProfessional = !empty($_POST["crp_crm"]); // Verifica se o campo CRP/CRM foi preenchido

    // Se for um profissional, verifica o campo CRP/CRM
    if ($isProfessional) {
        if (empty($_POST["crp_crm"])) {
            $response = ['status' => 'error', 'message' => 'O campo CRP/CRM é obrigatório para profissionais.'];
            echo json_encode($response);
            exit;
        }

        $crp_crm = $_POST["crp_crm"];
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE crp_crm = :crp_crm AND tipo_usuario = 'profissional'");
        $stmt->execute(['crp_crm' => $crp_crm]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_OBJ);

            // Verifica a senha com password_verify()
            if (password_verify($senha, $row->senha)) {
                $_SESSION["senha"] = $row->senha;
                $_SESSION["crp_crm"] = $crp_crm; // Armazena CRP/CRM na sessão
                $response = [
                    'status' => 'success',
                    'message' => 'Login bem-sucedido!',
                    'data' => $row // Retorna os dados do usuário
                ];
            } else {
                $response = ['status' => 'error', 'message' => 'Senha incorreta.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Usuário profissional não encontrado ou CRP/CRM inválido.'];
        }
    } else {
        // Se não for profissional, verifica o campo email
        if (empty($_POST["email"])) {
            $response = ['status' => 'error', 'message' => 'O campo email é obrigatório para usuários comuns.'];
            echo json_encode($response);
            exit;
        }

        $email = $_POST["email"];
       $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email ");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_OBJ);

            // Verifica a senha com password_verify()
            if (password_verify($senha, $row->senha)) {
                $_SESSION["senha"] = $row->senha;
                $_SESSION["email"] = $email; // Armazena email na sessão
                $response = [
                    'status' => 'success',
                    'message' => 'Login bem-sucedido!',
                    'data' => $row // Retorna os dados do usuário
                ];
            } else {
                $response = ['status' => 'error', 'message' => 'Senha incorreta.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Usuário comum não encontrado ou email inválido.'];
        }
    }

    echo json_encode($response);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido']);
}

?>
