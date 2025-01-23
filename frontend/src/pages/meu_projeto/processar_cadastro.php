<?php
// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    var_dump($_POST); // Exibe os dados do formulário (sem a imagem)
    var_dump($_FILES); // Exibe os dados da imagem enviada

    // Coleta os dados do formulário
    $profissao = $_POST['profissao'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $crp_crm = $_POST['crp_crm'];
    $celular = $_POST['celular'];
    $email = $_POST['email'];

    // Processa a foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_nome = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_ext = pathinfo($foto_nome, PATHINFO_EXTENSION);
        $foto_novo_nome = uniqid() . '.' . $foto_ext;
        $foto_destino = 'uploads/' . $foto_novo_nome;

        // Verifica se a imagem é válida
        if (in_array($foto_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($foto_tmp, $foto_destino)) {
                // Insere os dados no banco de dados
                $sql = "INSERT INTO profissionais (profissao, nome, descricao, crp_crm, foto,email,celular) 
                        VALUES (:profissao, :nome, :descricao, :crp_crm, :foto, :email, :celular)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':profissao' => $profissao,
                    ':nome' => $nome,
                    ':descricao' => $descricao,
                    ':crp_crm' => $crp_crm,
                    ':email' => $email,
                    ':celular' => $celular,
                    ':foto' => $foto_novo_nome
                ]);

                echo json_encode([
                    'status' => 'sucesso',
                    'mensagem' => 'Cadastro realizado com sucesso!'
                ]);
            } else {
                echo json_encode([
                    'status' => 'erro',
                    'mensagem' => 'Erro ao fazer upload da foto.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'erro',
                'mensagem' => 'Formato de imagem inválido.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Erro ao enviar a foto.'
        ]);
    }
}
?>
