<!DOCTYPE html>
<html lang="pt-BR">
<head>
<?php
include 'db.php';
session_start();

$usuarioLogado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$mensagemErro = isset($_SESSION['mensagem_erro']) ? $_SESSION['mensagem_erro'] : null;
unset($_SESSION['mensagem_erro']); // Limpe a mensagem após exibi-la

$tipoUsuario = isset($usuarioLogado['tipo_usuario']) ? $usuarioLogado['tipo_usuario'] : null; // Supondo que você tenha o tipo do usuário na sessão

// Obter comentários
$stmt = $pdo->query("SELECT * FROM comentarios WHERE resposta_id IS NULL ORDER BY data_criacao DESC");
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários</title>
    <link rel="stylesheet" href="./index.css"/>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Pegando os dados do localStorage
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    let id = '';
    let nameUser = '';
console.log(nameUser)
    // Verificar se o valor do localStorage existe antes de tentar fazer o parse
    if (usuarioLogadoRaw) {

        try {
            const usuarioLogado = JSON.parse(usuarioLogadoRaw);
            id = usuarioLogado.id || ''; // Aqui pegamos o id
            //nameUser = usuarioLogado.nome_usuario;
            const nameUser = usuarioLogado.tipo_usuario === 'profissional' ? usuarioLogado.nome : usuarioLogado.nome_usuario;

            console.log("nameUser:",nameUser)
            // Verificando se o objeto contém o campo id
            if (!id) {
                console.error('ID não encontrado no objeto usuário logado');
                alert('Por favor, faça login para comentar.');
                return;
            }

            console.log('ID do usuário logado:', usuarioLogado);

            const formComentario = document.querySelector('form[name="formComentario"]');

            if (formComentario) {
                formComentario.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const comentarioInput = formComentario.querySelector('textarea[name="comentario"]');
                    const comentario = comentarioInput.value;

                    // Enviando para o backend
                    fetch(formComentario.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            comentario: comentario,
                            usuario_id: id,
                            nome_usuario: nameUser
                        })
                    })
                    .then(response => {
                        // Verifica se a resposta é válida
                        if (!response.ok) {
                            throw new Error('Erro na requisição: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'sucesso') {
                            // Exibir sucesso ou atualizar a UI
                            alert('Comentário adicionado com sucesso!');
                            comentarioInput.value = '';
                            location.reload(); 
                        } else {
                            alert(data.mensagem || 'Erro ao adicionar comentário.');
                        }
                    })
                    .catch(error => console.error('Erro:', error));
                });
            }
        } catch (error) {
            console.error('Erro ao fazer o parse do JSON:', error);
        }
    } else {
        console.error('Nenhum usuário logado encontrado no localStorage');
        
        // Desativando o formulário de comentários
        const formComentario = document.querySelector('form[name="formComentario"]');
        if (formComentario) {
            const submitButton = formComentario.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true; // Desabilita o botão de enviar
            }
        }

        // Exibir uma mensagem para o usuário
        alert('Por favor, faça login para comentar.');
    }
});


   let comentariosExibidos = {};  // Objeto para armazenar o estado dos comentários já carregados

  function carregarRespostas(comentarioId, listaRespostas) {
    // Verifica se o usuário está logado
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    let id = '';
    if (usuarioLogadoRaw) {
        try {
            const usuarioLogado = JSON.parse(usuarioLogadoRaw);
            id = usuarioLogado.id || '';
        } catch (error) {
            console.error('Erro ao fazer o parse do JSON:', error);
        }
    }

    // Limpa a lista de respostas antes de carregar novas
    listaRespostas.innerHTML = '';

    fetch('get_respostas.php?comentario_id=' + comentarioId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na requisição: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log("Respostas carregadas para o comentário ID " + comentarioId + ":", data);  // Exibe todas as respostas no console
            if (listaRespostas && data.length > 0) {
                // Exibe a primeira resposta fixamente
                const primeiraResposta = data[0];
                const primeiraRespostaLi = document.createElement('li');
                primeiraRespostaLi.innerHTML = `<strong class="nome_usuario">${primeiraResposta.nome_usuario}</strong> <br> ${primeiraResposta.comentario}`;
                listaRespostas.appendChild(primeiraRespostaLi);

                // Adiciona campo de resposta para a primeira resposta
                adicionarCampoResposta(primeiraRespostaLi, primeiraResposta.id, id);

                // Carrega sub-respostas da primeira resposta
                carregarSubRespostas(primeiraResposta.id, primeiraRespostaLi, id);

                // Botão "Ver mais" para expandir as outras respostas
                const btnVerMais = document.createElement('a');
                btnVerMais.textContent = 'Ver Mais';
                btnVerMais.href = '#';
                btnVerMais.className = 'btn-ver-mais';
                listaRespostas.appendChild(btnVerMais);

                // Lista para as respostas adicionais (inicialmente escondida)
                const restantesRespostasList = document.createElement('ul');
                restantesRespostasList.style.display = 'none';

                data.slice(1).forEach(resposta => {
                    const li = document.createElement('li');
                    li.innerHTML = `<strong class="nome_usuario">${resposta.nome_usuario}</strong> <br> ${resposta.comentario}`;
                    restantesRespostasList.appendChild(li);

                    // Adiciona campo de resposta para cada resposta adicional
                    adicionarCampoResposta(li, resposta.id, id);

                    // Carrega respostas de subnível (respostas para esta resposta)
                    carregarSubRespostas(resposta.id, li, id);
                });

                listaRespostas.appendChild(restantesRespostasList);

                // Evento para expandir ou contrair respostas adicionais
                btnVerMais.onclick = function(e) {
                    e.preventDefault();
                    if (restantesRespostasList.style.display === 'none') {
                        restantesRespostasList.style.display = 'block';
                        btnVerMais.textContent = 'Ver Menos';
                    } else {
                        restantesRespostasList.style.display = 'none';
                        btnVerMais.textContent = 'Ver Mais';
                    }
                };
            }
        })
        .catch(error => console.error('Erro ao carregar respostas:', error));
}

// Função para carregar respostas de subnível
function carregarSubRespostas(respostaId, li, userId) {
    const subRespostasList = document.createElement('ul');
    subRespostasList.style.display = 'none';

    fetch('get_respostas.php?comentario_id=' + respostaId)
        .then(response => response.json())
        .then(data => {
            data.forEach(subResposta => {
                const subRespostaLi = document.createElement('li');
                subRespostaLi.innerHTML = `<strong class="nome_usuario">${subResposta.nome_usuario}</strong> <br> ${subResposta.comentario}`;
                
                // Adiciona campo de resposta para cada sub-resposta
                adicionarCampoResposta(subRespostaLi, subResposta.id, userId);
                
                subRespostasList.appendChild(subRespostaLi);
            });

            // Adiciona um botão "Ver Respostas" se houver sub-respostas
            if (data.length > 0) {
                const btnVerSubRespostas = document.createElement('a');
                btnVerSubRespostas.textContent = 'Ver Respostas';
                btnVerSubRespostas.href = '#';
                btnVerSubRespostas.className = 'btn-ver-sub-respostas';

                btnVerSubRespostas.onclick = function(e) {
                    e.preventDefault();
                    if (subRespostasList.style.display === 'none') {
                        subRespostasList.style.display = 'block';
                        btnVerSubRespostas.textContent = 'Esconder Respostas';
                    } else {
                        subRespostasList.style.display = 'none';
                        btnVerSubRespostas.textContent = 'Ver Respostas';
                    }
                };

                li.appendChild(btnVerSubRespostas);
            }

            li.appendChild(subRespostasList);
        })
        .catch(error => console.error('Erro ao carregar sub-respostas:', error));
}

// Função para adicionar campo de resposta
function adicionarCampoResposta(li, respostaId, userId) {
    const formResposta = document.createElement('form');
    formResposta.method = 'POST';
    formResposta.action = 'processar_comentario.php';
    formResposta.style.display = 'inline';

    if (userId) {
        formResposta.innerHTML = `
            <input type="hidden" name="resposta_id" value="${respostaId}">
            <textarea name="resposta" required placeholder="Digite sua resposta..."></textarea>
            <a href="#" class="btn-responder" onclick="enviarResposta(event, this.closest('form'))">Responder</a>
        `;
    } else {
        const span = document.createElement('span');
        span.textContent = 'Você precisa estar logado para responder.';
        span.style.color = 'red';
        li.appendChild(span);
    }

    li.appendChild(formResposta);
}

// Função para carregar respostas de subnível
function carregarSubRespostas(respostaId, li, userId) {
    const subRespostasList = document.createElement('ul');
    subRespostasList.style.display = 'none';

    fetch('get_respostas.php?comentario_id=' + respostaId)
        .then(response => response.json())
        .then(data => {
            console.log(data); // Verifique o conteúdo de data
            data.forEach(subResposta => {
                const subRespostaLi = document.createElement('li');
                subRespostaLi.innerHTML = `<strong class="nome_usuario">${subResposta.nome_usuario}</strong> <br> ${subResposta.comentario}`;
                
                // Adiciona campo de resposta para cada sub-resposta
                adicionarCampoResposta(subRespostaLi, subResposta.id, userId);
                
                subRespostasList.appendChild(subRespostaLi);
            });

            // Adiciona um botão "Ver Respostas" se houver sub-respostas
            if (data.length > 0) {
                const btnVerSubRespostas = document.createElement('a');
                btnVerSubRespostas.textContent = 'Ver Respostas';
                btnVerSubRespostas.href = '#';
                btnVerSubRespostas.className = 'btn-ver-sub-respostas';

                btnVerSubRespostas.onclick = function(e) {
                    e.preventDefault();
                    if (subRespostasList.style.display === 'none') {
                        subRespostasList.style.display = 'block';
                        btnVerSubRespostas.textContent = 'Esconder Respostas';
                    } else {
                        subRespostasList.style.display = 'none';
                        btnVerSubRespostas.textContent = 'Ver Respostas';
                    }
                };

                li.appendChild(btnVerSubRespostas);
            }

            li.appendChild(subRespostasList);
        })
        .catch(error => console.error('Erro ao carregar sub-respostas:', error));
}


function toggleRespostas(comentarioId) {
    const respostas = document.getElementById(`respostas-${comentarioId}`);
    const btnVerMais = document.querySelector(`#btn-ver-mais-${comentarioId}`);
    const btnVerMenos = document.querySelector(`#btn-ver-menos-${comentarioId}`);
    const respostaPrincipal = respostas.querySelector(".resposta-principal");

    // Oculta ou exibe todas as respostas exceto a primeira
    Array.from(respostas.children).forEach((resposta) => {
        if (resposta !== respostaPrincipal) {
            resposta.style.display = (resposta.style.display === 'none') ? 'block' : 'none';
        }
    });

    // Alterna entre os botões "Ver Mais" e "Ver Menos"
    if (respostas.style.display === 'none' || respostas.style.display === '') {
        respostas.style.display = 'block';
        btnVerMais.style.display = 'none';
        btnVerMenos.style.display = 'block';
    } else {
        respostas.style.display = 'none';
        btnVerMais.style.display = 'block';
        btnVerMenos.style.display = 'none';
    }
}


function enviarResposta(event, form) {
    event.preventDefault(); // Impede o comportamento padrão do link
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    let id = '';
    let nameUser = ''; // Inicializa nameUser como string vazia

    const comentarioInput = form.querySelector('textarea[name="resposta"]');
    const comentario = comentarioInput.value;
    const respostaId = form.querySelector('input[name="resposta_id"]').value;

    try {
        const usuarioLogado = JSON.parse(usuarioLogadoRaw);
        id = usuarioLogado.id || ''; // Aqui pegamos o id
        
           // Verifica se o status do usuário é "aprovado"
        if (usuarioLogado.status !== 'aprovado') {
            alert('Somente usuários aprovados podem enviar respostas.');
            return; // Interrompe a função caso o status não seja "aprovado"
        }

        // Remover const para usar a variável nameUser do escopo externo
        nameUser = usuarioLogado.tipo_usuario === 'profissional' ? usuarioLogado.nome : usuarioLogado.nome_usuario;

        console.log("Nome do usuário recuperado:", nameUser); // Log correto
            
    } catch (error) {
        console.error('Erro ao fazer o parse do JSON:', error);
    }

    // Preparar os dados para envio
    const dataToSend = {
        resposta: comentario,
        resposta_id: respostaId,
        usuario_id: id,
        nome_usuario: nameUser
    };

    // Log dos dados que serão enviados
    console.log("Dados que serão enviados para o backend:", dataToSend);

    // Enviando para o backend
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(dataToSend) // Usa os dados preparados
    })
    .then(response => {
        return response.text(); // Mude para .text() para ver o que está retornando
    })
    .then(text => {
        console.log("Resposta do servidor:", text); // Log da resposta
        const data = JSON.parse(text); // Tente fazer o parse aqui
        if (data.status === 'sucesso') {
            alert('Resposta enviada com sucesso!');
            comentarioInput.value = '';
        } else {
            alert(data.mensagem || 'Erro ao enviar resposta.');
        }
    })
    .catch(error => console.error('Erro:', error));
}

function esconderRespostas(respostasList) {
    // Lógica para esconder as respostas
    respostasList.style.display = 'none'; // ou outro método de ocultação
}
document.addEventListener('DOMContentLoaded', function() {
    const comentarios = document.querySelectorAll('[id^="respostas-"]');

    comentarios.forEach((comentario) => {
        const comentarioId = comentario.id.split('-')[1];
        carregarRespostas(comentarioId, comentario);
        comentario.style.display = 'block'; // Exibe as respostas automaticamente
    });
});


    </script>
</head>
<body>
    <div class="containerComentariopai">
        <div class="containerComentario">
            <h1>Comentários</h1>
                <form name="formComentario" method="POST" action="processar_comentario.php">
                    <textarea name="comentario" required placeholder="Digite seu comentário..."></textarea>
                    <input type="hidden" name="usuario_id" > <!-- Campo oculto para o ID do usuário -->
                    <button type="submit">Adicionar Comentário</button>
                </form>
        
            <h2>Lista de Comentários</h2>
            <ul>
            <?php foreach ($comentarios as $comentario): ?>
            <li>
                <strong class="nome_usuario">
                    <?= ($tipoUsuario === 'profissional') ? htmlspecialchars($comentario['nome']) : htmlspecialchars($comentario['nome_usuario']) ?>
                </strong> <br>
                <?= htmlspecialchars($comentario['comentario']) ?>

                <ul id="respostas-<?= $comentario['id'] ?>" class="respostas"></ul>
                <form name="formResposta" method="POST" action="processar_comentario.php" style="display:inline;" class="formResposta">
                    <input type="hidden" name="resposta_id" value="<?= $comentario['id'] ?>">
                    <textarea name="resposta" required placeholder="Digite sua resposta..."></textarea>
                    <li>
                        <a href="#" 
                            class="btn-responder" 
                            onclick="enviarResposta(event, this.closest('form'))"
                        >Responder</a>
                    </li>
                </form>
            </li>
            <?php endforeach; ?>

            </ul>
        </div>
    </div>
</body>
<script>

document.addEventListener('DOMContentLoaded', function () {
    // Pegando os dados do localStorage
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    let id = '';

    // Verifica se o usuário está logado
    if (usuarioLogadoRaw) {
        try {
            const usuarioLogado = JSON.parse(usuarioLogadoRaw);
            id = usuarioLogado.id || ''; // Aqui pegamos o id
            console.log(usuarioLogado);
            
            // Verifica se o usuário é do tipo profissional e se o status é aprovado
            if (!id  || usuarioLogado.status !== 'aprovado') {
                bloquearInteracao(); // Função para bloquear interação
            }

                  // Verifica se o usuário é do tipo profissional e se o status é aprovado ou rejeitado
            if (!id  || usuarioLogado.status === 'rejeitado') {
                bloquearInteracao(); // Função para bloquear interação
            }
        } catch (error) {
            console.error('Erro ao fazer o parse do JSON:', error);
            bloquearInteracao(); // Em caso de erro, bloquear interação
        }
    } else {
        // Caso não haja nenhum usuário logado no localStorage
        bloquearInteracao();
    }

    function bloquearInteracao() {
        // Bloquear formulários de resposta
        const formsResposta = document.querySelectorAll('form[name="formResposta"]');
        formsResposta.forEach(function(form) {
            desabilitarFormulario(form, 'Apenas usuários profissionais com status aprovado podem responder.');
        });

        // Bloquear formulários de comentário
        const formsComentario = document.querySelectorAll('form[name="formComentario"]');
        formsComentario.forEach(function(form) {
            desabilitarFormulario(form, 'Apenas usuários profissionais com status aprovado podem comentar.');
        });
    }

    function desabilitarFormulario(form, mensagem) {
        // Desabilita os campos e o botão de envio
        const textarea = form.querySelector('textarea');
        const button = form.querySelector('button[type="submit"]');
        
        if (textarea) textarea.disabled = true;
        if (button) button.disabled = true;

        // Exibe uma mensagem de bloqueio
        const message = document.createElement('p');
        message.textContent = mensagem;
        message.style.color = 'red';

        form.appendChild(message);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    
    if (usuarioLogadoRaw) {
        try {
            const usuarioLogado = JSON.parse(usuarioLogadoRaw);
            // Verifica se o status foi alterado no banco e atualiza o localStorage
            fetch(`/sifaps/backend/controllers/Admin/obterStatusUsuario.php?id=${usuarioLogado.id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const usuarioAtualizado = data.usuario; // Dados atualizados do banco
                        
                        // Verifica se os dados realmente mudaram antes de atualizar o localStorage
                        if (JSON.stringify(usuarioLogado) !== JSON.stringify(usuarioAtualizado)) {
                            // Atualiza o localStorage com os dados mais recentes
                            localStorage.setItem('usuario', JSON.stringify(usuarioAtualizado));

                            console.log("Dados atualizados no localStorage:", usuarioAtualizado);
                            
                            // Recarregar a página para refletir as mudanças
                            location.reload();  // Isso vai recarregar a página automaticamente
                        }
                    }
                })
                .catch(error => console.error("Erro ao obter dados atualizados do servidor:", error));
            
        } catch (error) {
            console.error('Erro ao fazer o parse do JSON:', error);
        }
    }
});

</script>

</html>
