<?php
/*include 'db.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os valores enviados
    $usuarioId = isset($_POST['usuario_id']) ? trim($_POST['usuario_id']) : null; // ID do usuário
    $nomeUsuario = isset($_POST['nome_usuario']) ? trim($_POST['nome_usuario']) : ''; // Nome do usuário
    $crpCrm = isset($_POST['crp_crm']) ? trim($_POST['crp_crm']) : 'Não informado'; // CRP/CRM do usuário
    
    // Verifica se a resposta está sendo enviada
    if (isset($_POST['resposta'])) {
        $resposta = $_POST['resposta'];
        $resposta_id = $_POST['resposta_id'];
        $id = $_POST['usuario_id'] ?? null; // ID do usuário, pode ser null


     // Verifica se a resposta e a ID do comentário estão presentes
        if (!empty($resposta) && !empty($resposta_id)) {
            $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, resposta_id, nome_usuario, usuario_id, data_criacao, crp_crm) VALUES (:comentario, :resposta_id,:nome_usuario, :usuario_id,:crp_crm,  NOW())");
            $stmt->execute([
                ':comentario' => $resposta,
                ':resposta_id' => $resposta_id,
                ':usuario_id' => $id, // Pode ser null
                ':nome_usuario' => $nomeUsuario, // Corrigido para usar a variável correta
                ':crp_crm' => $crpCrm
            ]);

            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Resposta adicionada com sucesso!']);
            
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro: resposta ou ID do comentário inválido.']);
        }
    }

    // Aqui lidamos com a adição de um novo comentário
    if (isset($_POST['comentario'])) {
        $comentario = trim($_POST['comentario']);

        if (!empty($comentario) && !empty($usuarioId) && !empty($nomeUsuario)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, usuario_id, nome_usuario, data_criacao) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$comentario, $usuarioId, $nomeUsuario]);

                echo json_encode(['status' => 'sucesso', 'mensagem' => 'Comentário adicionado com sucesso!']);
                
                exit;
            } catch (Exception $e) {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar comentário: ' . $e->getMessage()]);
                exit;
            }
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos.']);
            exit;
        }
    }

    // Se nenhuma condição acima foi atendida
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados não foram enviados corretamente.']);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Método de requisição inválido.']);
}*/

?>
<?php
include 'db.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os valores enviados
    $usuarioId = isset($_POST['usuario_id']) ? trim($_POST['usuario_id']) : null;
    $nomeUsuario = isset($_POST['nome_usuario']) ? trim($_POST['nome_usuario']) : '';
    $crpCrm = isset($_POST['crp_crm']) ? trim($_POST['crp_crm']) : '';

    // Verifica se é uma resposta
    if (isset($_POST['resposta']) && isset($_POST['resposta_id'])) {
        $resposta = trim($_POST['resposta']);
        $resposta_id = trim($_POST['resposta_id']);

        if (!empty($resposta) && !empty($resposta_id)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO comentarios 
                    (comentario, resposta_id, nome_usuario, usuario_id, crp_crm, data_criacao) 
                    VALUES (:comentario, :resposta_id, :nome_usuario, :usuario_id, :crp_crm, NOW())
                ");
                $stmt->execute([
                    ':comentario' => $resposta,
                    ':resposta_id' => $resposta_id,
                    ':usuario_id' => $usuarioId,
                    ':nome_usuario' => $nomeUsuario,
                    ':crp_crm' => $crpCrm
                ]);

                echo json_encode(['status' => 'sucesso', 'mensagem' => 'Resposta adicionada com sucesso!']);
                exit;
            } catch (Exception $e) {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar resposta: ' . $e->getMessage()]);
                exit;
            }
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Dados da resposta estão incompletos.']);
            exit;
        }
    }

    // Caso seja um novo comentário
    if (isset($_POST['comentario'])) {
        $comentario = trim($_POST['comentario']);

        if (!empty($comentario) && !empty($usuarioId) && !empty($nomeUsuario)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO comentarios 
                    (comentario, usuario_id, nome_usuario, crp_crm, data_criacao) 
                    VALUES (:comentario, :usuario_id, :nome_usuario, :crp_crm, NOW())
                ");
                $stmt->execute([
                    ':comentario' => $comentario,
                    ':usuario_id' => $usuarioId,
                    ':nome_usuario' => $nomeUsuario,
                    ':crp_crm' => $crpCrm
                ]);

                echo json_encode(['status' => 'sucesso', 'mensagem' => 'Comentário adicionado com sucesso!']);
                exit;
            } catch (Exception $e) {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar comentário: ' . $e->getMessage()]);
                exit;
            }
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Dados do comentário estão incompletos.']);
            exit;
        }
    }

    // Caso nenhum dado relevante tenha sido enviado
    echo json_encode(['status' => 'erro', 'mensagem' => 'Nenhum dado válido foi enviado.']);
    exit;
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Método de requisição inválido.']);
    exit;
}
