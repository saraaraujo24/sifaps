async function enviarCadastro(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const formData = new FormData(document.getElementById('formCadas-cadastro'));
    const data = Object.fromEntries(formData);

    console.log('Dados que estão sendo enviados para o banco de dados:', data);
    
    try {
        const response = await fetch('/sifaps/backend/controllers/Perfil/atualizar_usuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        console.log('Resposta do servidor:', result); // Adicione este log

        if (result.status === "success") {
            alert("Dados atualizados com sucesso!");
            setTimeout(() => {
                location.reload(); // Recarrega a página
            }, 2000);
        } else {
            // Mostra a mensagem de erro em um alert
            const errorMessage = result.message || 'Ocorreu um erro desconhecido.';
            console.error(errorMessage);
            alert(errorMessage); // Usar alert para mostrar a mensagem de erro
        }
    } catch (error) {
        console.error('Erro ao atualizar dados do usuário:', error);
        alert('Erro ao atualizar dados do usuário.');
    }
}

async function carregarDados(userId) {
    try {
        const response = await fetch(`/sifaps/backend/controllers/Perfil/buscar_usuario.php?id=${userId}`);
        
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.status === "success") {
            const usuario = result.data;

            // Atualizar a mensagem de boas-vindas
            //document.querySelector('.welcome-text').textContent = `Seja Bem-Vinda(o), ${usuario.nome}!`;

            // Preencher os campos do formulário
            document.getElementById('cadastro-nome').value = usuario.nome;
            document.getElementById('cadastro-email').value = usuario.email;
            document.getElementById('telefone').value = usuario.celular;
            document.getElementById('cpf').value = usuario.cpf;
            document.getElementById('estado').value = usuario.estado;
            document.getElementById('cidade').value = usuario.cidade;
            document.getElementById('nome_usuario').value = usuario.nome_usuario;
            document.getElementById('id').value = usuario.id;

            // Verifica se o usuário é do tipo 'profissional'
            if (usuario.tipo_usuario === 'profissional') {
                // Preenche CRP/CRM e Profissão
                document.getElementById('cadastro-crp-crm').value = usuario.crp_crm;
                document.getElementById('cadastro-profissao').value = usuario.profissao;

                // Garantir que os campos de CRP/CRM e Profissão estão visíveis
                document.getElementById('cadastro-crp-crm').style.display = 'block';
                document.querySelector('.formCadasP-group').style.display = 'block';
                document.querySelector('label[for="cadastro-crp-crm"]').style.display = 'block';
                
                // Deixa os campos obrigatórios
                document.getElementById('cadastro-crp-crm').setAttribute('required', true);
                document.getElementById('cadastro-profissao').setAttribute('required', true);

                // Ocultar o campo "Nome de usuário" e remover a obrigatoriedade
                document.getElementById('nome_usuario').parentElement.style.visibility = 'hidden';
                document.getElementById('nome_usuario').removeAttribute('required');
                console.log('Campo "Nome de usuário" ocultado para profissional.');
                
            } else {
                // Se não for profissional, exibir o campo de Nome de usuário
                document.getElementById('nome_usuario').parentElement.style.visibility = 'visible';
                document.getElementById('nome_usuario').setAttribute('required', true);
                
                // Se não for profissional, exibir CRP/CRM e Profissão
                document.getElementById('cadastro-crp-crm').style.display = 'none';
                document.querySelector('.formCadasP-group').style.display = 'none';
                document.querySelector('label[for="cadastro-crp-crm"]').style.display = 'none';
                
                // Remove a obrigatoriedade dos campos
                document.getElementById('cadastro-crp-crm').removeAttribute('required');
                document.getElementById('cadastro-profissao').removeAttribute('required');
            }

            return usuario; // Retorna o objeto de dados do usuário
        } else {
            console.error(result.message);
            document.getElementById('output').innerText = result.message;
            return null; // Retorna null se não encontrar o usuário
        }
    } catch (error) {
        console.error('Erro ao carregar dados do usuário:', error);
        document.getElementById('output').innerText = 'Erro ao carregar dados do usuário.';
        return null; // Retorna null em caso de erro
    }
}

window.onload = function() {
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    if (usuarioLogadoRaw) {
        try {
            const usuarioLogado = JSON.parse(usuarioLogadoRaw);
            const userId = usuarioLogado.id; // Pegando o ID do usuário

            if (userId) {
                // Carregar dados do usuário logado
                carregarDados(userId).then(usuario => {
                    if (usuario.tipo_usuario === 'profissional') {
                            // Ocultar o campo "Nome de usuário"
                            document.getElementById('nome_usuario').parentElement.style.display = 'none';
                            console.log('Campo "Nome de usuário" ocultado para profissional.');
                        } else {
                            // Mostrar o campo "Nome de usuário"
                            document.getElementById('nome_usuario').parentElement.style.display = 'block';
                            console.log('Campo "Nome de usuário" visível para usuário comum.');
                        }
                });
            } else {
                console.error('ID do usuário não encontrado no objeto logado.');
            }
        } catch (error) {
            console.error('Erro ao fazer o parse do usuário logado:', error);
        }
    } else {
        console.error('Nenhum usuário logado encontrado no localStorage.');
    }
};