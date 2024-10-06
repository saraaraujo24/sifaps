nao repeti resposta

<?php
include 'db.php';

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
            border-radius: 8px;
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #4cae4c;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #fff;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .respostas {
            margin-left: 20px;
            border-left: 2px solid #5cb85c;
            padding-left: 10px;
        }
    </style>
    <script>
   let comentariosExibidos = {};  // Objeto para armazenar o estado dos comentários já carregados

        function carregarRespostas(comentarioId, listaRespostas) {

            if (comentariosExibidos[comentarioId]) {
                listaRespostas.style.display = 'block';  // Mostra a lista de respostas se já carregadas
                return;  // Retorna para não carregar novamente
            }

            fetch('get_respostas.php?comentario_id=' + comentarioId)
                .then(response => response.json())
                .then(data => {
                    if (listaRespostas) {
                        data.forEach(resposta => {
                            const li = document.createElement('li');
                            li.textContent = resposta.comentario;

                            const respostasList = document.createElement('ul');
                            respostasList.id = 'respostas-' + resposta.id;
                            li.appendChild(respostasList);

                            const btnVerRespostas = document.createElement('button');
                            btnVerRespostas.textContent = 'Ver Respostas';
                            btnVerRespostas.onclick = function() {
                                carregarRespostas(resposta.id, respostasList);
                            };
                            li.appendChild(btnVerRespostas);

                            const formResposta = document.createElement('form');
                            formResposta.method = 'POST';
                            formResposta.action = 'processar_comentario.php';
                            formResposta.style.display = 'inline';
                            formResposta.innerHTML = `
                                <input type="hidden" name="resposta_id" value="${resposta.id}">
                                <textarea name="resposta" required placeholder="Digite sua resposta..."></textarea>
                                <button type="submit">Responder</button>
                            `;
                            li.appendChild(formResposta);
                            listaRespostas.appendChild(li);
                        });

                         // Marcar que as respostas já foram exibidas
                         comentariosExibidos[comentarioId] = true;
                    }
                    listaRespostas.style.display = 'block';
                });
        }
    </script>
</head>
<body>
    <h1>Comentários</h1>
    <form method="POST" action="processar_comentario.php">
        <textarea name="comentario" required placeholder="Digite seu comentário..."></textarea>
        <button type="submit">Adicionar Comentário</button>
    </form>

    <h2>Lista de Comentários</h2>
    <ul>
        <?php foreach ($comentarios as $comentario): ?>
            <li>
                <?= htmlspecialchars($comentario['comentario']) ?>
                <button onclick="carregarRespostas(<?= $comentario['id'] ?>, document.getElementById('respostas-<?= $comentario['id'] ?>'))">Ver Respostas</button>
                <ul id="respostas-<?= $comentario['id'] ?>" class="respostas"></ul>
                <form method="POST" action="processar_comentario.php" style="display:inline;">
                    <input type="hidden" name="resposta_id" value="<?= $comentario['id'] ?>">
                    <textarea name="resposta" required placeholder="Digite sua resposta..."></textarea>
                    <button type="submit">Responder</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
