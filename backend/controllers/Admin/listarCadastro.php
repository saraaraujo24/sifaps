<?php
include("../../api/conn.php");

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? $data['id'] : null;

$PDO = db_connect();
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

// Alterar a consulta para buscar apenas cadastros pendentes
$sql = "SELECT idProfissional, nome, email, celular, cpf, cidade, estado, crp_crm, profissao,data_cadastro 
        FROM cadastroProf WHERE status = 'pendente'";
$result = $PDO->query($sql);

if ($result) {
    $usuarios = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $usuarios[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $usuarios]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao buscar dados."]);
}
?>
<?php
/*include("../../api/conn.php");

header('Content-Type: application/json');

$PDO = db_connect();
if (!$PDO) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados."]);
    exit;
}

// Consulta para buscar cadastros com status 'aprovado' e 'rejeitado'
$sql = "SELECT idProfissional, nome, email, celular, cpf, cidade, estado, crp_crm, profissao, status 
        FROM cadastroProf WHERE status IN ('aprovado', 'rejeitado')";
$result = $PDO->query($sql);

if ($result) {
    $usuarios = [
        'aprovados' => [],
        'rejeitados' => []
    ];
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['status'] === 'aprovado') {
            $usuarios['aprovados'][] = $row;
        } elseif ($row['status'] === 'rejeitado') {
            $usuarios['rejeitados'][] = $row;
        }
    }
    
    echo json_encode(["status" => "success", "data" => $usuarios]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao buscar dados."]);
}*/
?>

