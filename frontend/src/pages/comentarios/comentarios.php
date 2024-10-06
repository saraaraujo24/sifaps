<?php
/*include 'db.php';

// Adicionar comentário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentario = $_POST['comentario'];
    $stmt = $pdo->prepare("INSERT INTO comentarios (comentario) VALUES (:comentario)");
    $stmt->execute(['comentario' => $comentario]);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Adicionar resposta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resposta'])) {
    $resposta = $_POST['resposta'];
    $resposta_id = $_POST['resposta_id'];
    $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, resposta_id) VALUES (:comentario, :resposta_id)");
    $stmt->execute(['comentario' => $resposta, 'resposta_id' => $resposta_id]);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Obter comentários
$stmt = $pdo->query("SELECT * FROM comentarios WHERE resposta_id IS NULL ORDER BY data_criacao DESC");
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários</title>
    <script>
        function carregarRespostas(comentarioId, listaRespostas) {
            fetch('get_respostas.php?comentario_id=' + comentarioId)
                .then(response => response.json())
                .then(data => {
                    // Verifica se a lista de respostas existe
                    if (listaRespostas) {
                        data.forEach(resposta => {
                            const li = document.createElement('li');
                            li.textContent = resposta.comentario;

                            // Verifica se já existem respostas para esta resposta
                            const respostasList = document.createElement('ul');
                            respostasList.id = 'respostas-' + resposta.id;
                            li.appendChild(respostasList);

                            // Botão para carregar respostas da resposta
                            const btnVerRespostas = document.createElement('button');
                            btnVerRespostas.textContent = 'Ver Respostas';
                            btnVerRespostas.onclick = function() {
                                carregarRespostas(resposta.id, respostasList);
                            };
                            li.appendChild(btnVerRespostas);

                            // Formulário para responder a uma resposta
                            const formResposta = document.createElement('form');
                            formResposta.method = 'POST';
                            formResposta.style.display = 'inline';
                            formResposta.innerHTML = `
                                <input type="hidden" name="resposta_id" value="${resposta.id}">
                                <textarea name="resposta" required></textarea>
                                <button type="submit">Responder</button>
                            `;
                            li.appendChild(formResposta);
                            listaRespostas.appendChild(li);
                        });
                    }
                });
        }
    </script>
</head>
<body>
    <h1>Comentários</h1>
    <form method="POST">
        <textarea name="comentario" required></textarea>
        <button type="submit">Adicionar Comentário</button>
    </form>

    <h2>Lista de Comentários</h2>
    <ul>
        <?php foreach ($comentarios as $comentario): ?>
            <li>
                <?= htmlspecialchars($comentario['comentario']) ?>
                <button onclick="carregarRespostas(<?= $comentario['id'] ?>, document.getElementById('respostas-<?= $comentario['id'] ?>'))">Ver Respostas</button>
                <ul id="respostas-<?= $comentario['id'] ?>"></ul>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="resposta_id" value="<?= $comentario['id'] ?>">
                    <textarea name="resposta" required></textarea>
                    <button type="submit">Responder</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
*/
