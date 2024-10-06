<?php
include("../../api/conn.php");

header('Content-Type: application/json');

$nome = isset($_POST['nome']) ? $_POST['nome'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$senha = isset($_POST['senha']) ? $_POST['senha'] : null;
$celular = isset($_POST['celular']) ? $_POST['celular'] : null;
$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;
$cidade = isset($_POST['cidade']) ? $_POST['cidade'] : null;
$estado = isset($_POST['estado']) ? $_POST['estado'] : null;
$crp_crm = isset($_POST['crp_crm']) ? $_POST['crp_crm'] : null;
$profissao = isset($_POST['profissao']) ? $_POST['profissao'] : null;
$data = isset($_POST['data_cadastro']) ? $_POST['data_cadastro'] : null;

if (empty($_POST)) {
    echo json_encode(["status" => "error", "message" => "Nenhum dado recebido."]);
    exit;
}

if (empty($nome) || empty($email) || empty($senha) || empty($celular) || empty($cpf) || empty($cidade) || empty($estado) || empty($crp_crm) || empty($profissao) || empty($data)) {
    echo json_encode(["status" => "error", "message" => "Todos os campos devem ser preenchidos."]);
    exit;
}

$PDO = db_connect();
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

$sql = "INSERT INTO cadastroProf (nome, email, senha, celular, cpf, cidade, estado, crp_crm, profissao, data_cadastro, status)
        VALUES (:nome, :email, :senha, :celular, :cpf, :cidade, :estado, :crp_crm, :profissao, :data, 'pendente')";

$stmt = $PDO->prepare($sql);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':senha', $senha);
$stmt->bindParam(':celular', $celular);
$stmt->bindParam(':cpf', $cpf);
$stmt->bindParam(':cidade', $cidade);
$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':crp_crm', $crp_crm);
$stmt->bindParam(':profissao', $profissao);
$stmt->bindParam(':data', $data);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Cadastro realizado com sucesso! Aguardando aprovação."]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao cadastrar", "details" => $stmt->errorInfo()]);
}
?>
