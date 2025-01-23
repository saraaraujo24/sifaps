<?php
include 'config.php';
// Exemplo de como verificar se o usuário está logado e buscar os dados

session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Usuário está logado, recupera o ID do usuário
$user_id = $_SESSION['user_id'];

// Conecta ao banco de dados (substitua com suas credenciais)
include 'config.php';

// Busca os dados do usuário no banco de dados
$query = $pdo->prepare("SELECT nome, profissao, descricao, crp_crm,email,celular, foto FROM profissionais WHERE user_id = ?");
$query->execute([$user_id]);

// Se o usuário for encontrado
if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $nome = $row['nome'];
    $profissao = $row['profissao'];
    $descricao = $row['descricao'];
    $crp_crm = $row['crp_crm']; 
    $email = $row['email'];
    $celular = $row['celular'];
    $foto = $row['foto'];
} else {
    // Caso o usuário não seja encontrado, redireciona ou exibe erro
    echo "Usuário não encontrado.";
    exit;
}

?>