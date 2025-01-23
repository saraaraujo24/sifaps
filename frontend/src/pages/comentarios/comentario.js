document.addEventListener('DOMContentLoaded', function () {
    // Pegando os dados do localStorage
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    console.log(usuarioLogadoRaw)
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
            desabilitarFormulario(form, 'Apenas profissionais com status aprovado e usuários logados podem responder.');
        });

        // Bloquear formulários de comentário
        const formsComentario = document.querySelectorAll('form[name="formComentario"]');
        formsComentario.forEach(function(form) {
            desabilitarFormulario(form, 'Apenas profissionais com status aprovado e usuários logados podem comentar.');
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
    console.log(usuarioLogadoRaw)
    
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

document.addEventListener('DOMContentLoaded', function () {
    // Pegando os dados do localStorage
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    let id = '';
    let nameUser = '';
    

    // Verificar se o valor do localStorage existe antes de tentar fazer o parse
    if (usuarioLogadoRaw) {

        try {
            const usuarioLogado = JSON.parse(usuarioLogadoRaw);
            id = usuarioLogado.id || ''; // Aqui pegamos o id
            //nameUser = usuarioLogado.nome_usuario;
            const nameUser = usuarioLogado.tipo_usuario === 'profissional' ? usuarioLogado.nome : usuarioLogado.nome_usuario;
            const crp_crm = usuarioLogado.crp_crm ;

            console.log("CRP/CRM:", crp_crm);
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

                // Criando os dados que serão enviados
                const bodyData = new URLSearchParams({
                    comentario: comentario,
                    usuario_id: id,
                    nome_usuario: nameUser,
                    crp_crm: crp_crm
                });
                                // Mostrando no console os dados que serão enviados
                console.log("Dados enviados para o backend:", Object.fromEntries(bodyData));

                    // Enviando para o backend
                    fetch(formComentario.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            comentario: comentario,
                            usuario_id: id,
                            nome_usuario: nameUser,
                            crp_crm:crp_crm
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
    const respostasExibidas = new Set();

    // Pegando os dados do localStorage
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    let id = '';

    // Verifica se o usuário está logado
    if (usuarioLogadoRaw) {
        try {
            const usuarioLogado = JSON.parse(usuarioLogadoRaw);
            id = usuarioLogado.id || ''; // Aqui pegamos o id
            const nameUser = usuarioLogado.tipo_usuario === 'profissional' ? usuarioLogado.nome : usuarioLogado.nome_usuario;
            console.log('ID do usuário logado Resposta:', usuarioLogado);
        } catch (error) {
            console.error('Erro ao fazer o parse do JSON:', error);
        }
    } else {
        console.log('Nenhum usuário logado encontrado no localStorage');
    }

    // Limpa a lista de respostas antes de carregar novas
    listaRespostas.innerHTML = '';

    fetch('/sifaps/backend/controllers/Comentario/get_respostas.php?comentario_id=' + comentarioId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na requisição: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (listaRespostas) {
                data.forEach(resposta => {
                    // Verifica se a resposta já foi exibida
                    if (!respostasExibidas.has(resposta.comentario)) {
                        respostasExibidas.add(resposta.comentario);

                        const li = document.createElement('li');
    
                        li.innerHTML = `
                            <strong class="nome_usuario">${resposta.nome_usuario} </strong>
                            <strong class="nome_usuario">CRP: ${resposta.crp_crm}</strong>
                            <br>
                            <p class="comentario">${resposta.comentario}</p>
                        `;

                        if (id === resposta.usuario_id) {
                            const excluirBtn = document.createElement('button');
                            excluirBtn.className = 'btn-excluir';
                            excluirBtn.textContent = 'Excluir';
                            excluirBtn.onclick = () => excluirResposta(resposta.id);
                            li.appendChild(excluirBtn);
                        }

                        const respostasList = document.createElement('ul');
                        respostasList.id = 'respostas-' + resposta.id;
                        li.appendChild(respostasList);

                        const formResposta = document.createElement('form');
                        formResposta.method = 'POST';
                        formResposta.action = 'processar_comentario.php';
                        formResposta.style.display = 'inline';

                        // Verifica se o ID do usuário é diferente de null
                        if (id) {
                            // Restaurar o conteúdo inicial do formulário
                            formResposta.innerHTML = `
                                <input type="hidden" name="resposta_id" value="${resposta.id}">
                                <textarea name="resposta" required placeholder="Digite sua resposta..."></textarea>
                                <a href="#" class="btn-responder" onclick="enviarResposta(event, this.closest('form'))">Responder</a><br></br>
                            `;

                        } else {
                            // Se o ID for nulo, desabilita o botão e exibe uma mensagem
                            const span = document.createElement('span');
                            span.textContent = 'Você precisa estar logado para responder.';
                            span.style.color = 'red';
                            li.appendChild(span);
                        }

                        
                        li.appendChild(formResposta);
                        listaRespostas.appendChild(li);

                        
                        const btnVerRespostas = document.createElement('a');
                        btnVerRespostas.textContent = 'Ver Respostas';
                        btnVerRespostas.href = '#';
                        btnVerRespostas.className = 'btn-ver-respostas';
                        btnVerRespostas.onclick = function() {
                            if (btnVerRespostas.textContent === 'Ver Respostas') {
                                carregarRespostas(resposta.id, respostasList);
                                btnVerRespostas.textContent = 'Ver Menos ';
                            } else {
                                esconderRespostas(respostasList); // Função para esconder as respostas
                                btnVerRespostas.textContent = 'Ver Respostas';
                            }
                        };
                        li.appendChild(btnVerRespostas);
                    }
                });
            }
            listaRespostas.style.display = 'block';
        })
        .catch(error => console.error('Erro ao carregar respostas:', error));
}

function toggleRespostas(comentarioId, link) {
    const listaRespostas = document.getElementById('respostas-' + comentarioId);

    // Verificar se a lista de respostas já está carregada
    if (listaRespostas.style.display === 'none' || listaRespostas.style.display === '') {
        listaRespostas.style.display = 'block'; // Mostrar as respostas
        link.textContent = 'Ver Menos'; // Alterar o texto para "Ver Menos"
        
        // Carregar as respostas se ainda não foram carregadas
        carregarRespostas(comentarioId, listaRespostas);
    } else {
        listaRespostas.style.display = 'none'; // Ocultar as respostas
        link.textContent = 'Ver Respostas'; // Alterar o texto para "Ver Respostas"
    }
}

function enviarResposta(event, form) {
    event.preventDefault(); // Impede o comportamento padrão do link
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    let id = '';
    let nameUser = ''; // Inicializa nameUser como string vazia
    let crp_crm =  '';

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

        // Definindo o nome do usuário
        nameUser = usuarioLogado.tipo_usuario === 'profissional' ? usuarioLogado.nome : usuarioLogado.nome_usuario;
         // Definindo o valor de crp_crm
        crp_crm = usuarioLogado.crp_crm ; // Define o CRP/CRM ou usa um valor padrão


        console.log("Nome do usuário recuperado:", nameUser);
    } catch (error) {
        console.error('Erro ao fazer o parse do JSON:', error);
    }

    // Preparar os dados para envio
    const dataToSend = {
        resposta: comentario,
        resposta_id: respostaId,
        usuario_id: id,
        nome_usuario: nameUser,
        crp_crm: crp_crm 
    };

    // Log dos dados que serão enviados
    console.log("Dados que serão enviados para o backend:", dataToSend);

    // Enviando para o backend
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(dataToSend)
    })
    .then(response => response.json())
    .then(data => {
        console.log("Resposta do servidor:", data);
        if (data.status === 'sucesso') {
            // Limpar o campo de resposta após sucesso
            comentarioInput.value = ''; // Limpa o campo de texto de resposta
            alert('Resposta enviada com sucesso!');

            // Aqui, podemos também adicionar a resposta na lista sem precisar recarregar a página
            const listaRespostas = document.getElementById(`respostas-${respostaId}`);
            if (listaRespostas) {
                const novaResposta = document.createElement('li');
                novaResposta.innerHTML = `
                    <strong class="nome_usuario">${nameUser} (${crp_crm})</strong> <br> 
                    ${comentario}
                `;
                listaRespostas.appendChild(novaResposta);
            }

            // Recarregar a página se necessário
            setTimeout(() => {
                location.reload(); // Opcional: recarrega a página após a resposta ser enviada
            }, 500);
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

/* Excluir comentario*/ 
function excluirResposta(comentarioId) {
    // Confirmação antes de excluir
    if (!confirm('Tem certeza de que deseja excluir este comentário?')) {
        return;
    }

    // Faz a requisição para excluir o comentário no backend
    fetch(`/sifaps/backend/controllers/Comentario/delete_comentario.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            comentario_id: comentarioId,
        }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao excluir o comentário: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'sucesso') {
                alert('Comentário excluído com sucesso!');
                location.reload(); // Atualizar a página para refletir a exclusão
            } else {
                alert(data.mensagem || 'Erro ao excluir o comentário.');
            }
        })
        .catch(error => {
            console.error('Erro ao excluir o comentário:', error);
        });
}

function excluirComentario(comentarioId, comentarioElemento) {
    if (confirm('Tem certeza que deseja excluir este comentário?')) {
        // Chama uma requisição para excluir o comentário no backend
        fetch('excluir_comentario.php?id=' + comentarioId, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover o comentário da tela
                    comentarioElemento.remove();
                } else {
                    alert('Erro ao excluir o comentário.');
                }
            });
    }
}



