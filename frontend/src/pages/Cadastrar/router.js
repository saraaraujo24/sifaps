async function enviarCadastro(event) {
    event.preventDefault();

    const form = document.getElementById('formCadas-cadastro');
    const formData = new FormData(form);

    // Adiciona a data atual ao FormData
    const dataAtual = new Date().toISOString().slice(0, 19).replace('T', ' ');
    formData.append('data_cadastro', dataAtual);

    // Mostra tudo que está sendo enviado no console
    for (const [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    try {
        const response = await fetch(form.getAttribute('data-endpoint'), {
            method: 'POST',
            body: formData
        });

        const contentType = response.headers.get("content-type");
        let responseJson;

        if (contentType && contentType.includes("application/json")) {
            responseJson = await response.json();
        } else {
            const responseText = await response.text();
            console.error('Resposta do backend não é JSON:', responseText);
            return;
        }

        if (responseJson.status === "success") {
            alert('Cadastro realizado com sucesso! Aguardando aprovação.');
            location.reload(); // Recarregar a página após sucesso
        } else {
            alert('Erro: ' + responseJson.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao enviar os dados.');
    }
}

// Função para listar usuários
/*async function listarUsuarios() {
    try {
        const response = await fetch('/sifaps/backend/controllers/cadastro/listar.php');
        const responseJson = await response.json();

        if (responseJson.status === "success") {
            const lista = document.getElementById('lista-usuarios');
            lista.innerHTML = '';
            responseJson.data.forEach(usuario => {
                const item = document.createElement('li');
                item.textContent = `${usuario.nome} - ${usuario.email} `;

                // Cria o botão de deletar
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Deletar';
                deleteButton.onclick = () => deletarUsuario(usuario.idProfissional); // Chama a função deletar


                // Botão de editar
                const editeButton = document.createElement('button');
                editeButton.textContent = 'Editar';
                editeButton.onclick = () => abrirModalEditar(usuario.idProfissional);

                item.appendChild(deleteButton);
                item.appendChild(editeButton);
                lista.appendChild(item);
            });
        } else {
            alert('Erro ao listar os dados: ' + responseJson.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao listar os dados.');
    }
}
*/

// Função para salvar a edição
async function salvarEdicao() {
    // Coleta os dados do formulário
    const idProfissional = document.getElementById('idProfissional').value;
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const celular = document.getElementById('celular').value;
    const cpf = document.getElementById('cpf').value;
    const cidade = document.getElementById('cidade').value;
    const estado = document.getElementById('estado').value;
    const crp_crm = document.getElementById('crp_crm').value;
    const profissao = document.getElementById('profissao').value;

    // Cria o objeto de dados
    const dados = {
        idProfissional,
        nome,
        email,
        celular,
        cpf,
        cidade,
        estado,
        crp_crm,
        profissao
    };

    // Verifica os dados antes de enviar
    console.log('Dados enviados para o backend:', dados);

    try {
        // Envia a requisição para o backend
        const response = await fetch('/sifaps/backend/controllers/cadastro/update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(dados)
        });

        const result = await response.json();
        console.log('Resposta do servidor:', result);
        
        if (result.status === 'success') {
            console.log(result.status);
            alert('Cadastro atualizado com sucesso!');
            fecharModal(); // Fecha o modal e atualiza a lista
        } else {
            alert('Erro ao salvar os dados: ' + result.message);
        }
    } catch (error) {
        console.error('Erro ao salvar os dados:', error);
        alert('Erro ao salvar os dados.');
    }
}

// Deletar usuário
async function deletarUsuario(idProfissional) {
    if (!confirm('Tem certeza que deseja deletar este usuário?')) {
        return;
    }

    try {
        const response = await fetch('/sifaps/backend/controllers/cadastro/deletar.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ idProfissional })
        });

        const result = await response.json();

        if (result.status === 'success') {
            alert('Usuário deletado com sucesso');
            listarUsuarios(); // Atualiza a lista após a exclusão
        } else {
            alert('Erro ao deletar: ' + result.message);
        }
    } catch (error) {
        console.error('Erro ao deletar:', error);
        alert('Erro ao deletar o usuário.');
    }
}


// Função para abrir o modal e carregar os dados
async function abrirModalEditar(idProfissional) {
    console.log('Abrindo modal para ID:', idProfissional);
    try {
        const response = await fetch(`/sifaps/backend/controllers/cadastro/get.php?idProfissional=${idProfissional}`);
        const result = await response.json();
        console.log('Dados recebidos:', result);

        if (result.status === 'success') {
            document.getElementById('editModal').style.display = 'flex';

            document.getElementById('idProfissional').value = idProfissional;
            document.getElementById('nome').value = result.data.nome;
            document.getElementById('email').value = result.data.email;
            document.getElementById('celular').value = result.data.celular;
            document.getElementById('cpf').value = result.data.cpf;
            document.getElementById('cidade').value = result.data.cidade;
            document.getElementById('estado').value = result.data.estado;
            document.getElementById('crp_crm').value = result.data.crp_crm;
            document.getElementById('profissao').value = result.data.profissao;
        } else {
            alert('Erro ao carregar os dados: ' + result.message);
        }
    } catch (error) {
        console.error('Erro ao carregar os dados:', error);
        alert('Erro ao carregar os dados.');
    }
}

// Função para fechar o modal
function fecharModal() {
    document.getElementById('editModal').style.display = 'none';
    listarUsuarios(); // Atualiza a lista após fechar o modal
}

document.getElementById('closeModal').onclick = fecharModal;

// Fechar o modal clicando fora da caixa de diálogo
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        fecharModal();
    }
}

// Carregar os usuários ao carregar a página
document.addEventListener('DOMContentLoaded', listarUsuarios);
