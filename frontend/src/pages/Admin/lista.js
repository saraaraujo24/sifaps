document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('/sifaps/backend/controllers/Admin/listarRetor.php');
    const result = await response.json();

    if (result.status === 'success') {
        const aprovadosList = document.getElementById('aprovadosList');
        const rejeitadosList = document.getElementById('rejeitadosList');

        // Listar profissionais aprovados
        result.data.aprovados.forEach(profissional => {
            const li = document.createElement('li');
            li.innerHTML = `${profissional.nome}
                <button class="rejeitar-prof" onclick="reverterStatus(${profissional.id}, 'rejeitado')">Rejeitar</button>
             `;
            aprovadosList.appendChild(li);
        });

        // Listar profissionais rejeitados
        result.data.rejeitados.forEach(profissional => {
            const li = document.createElement('li');
            li.innerHTML = `${profissional.nome}
                <button class="aprovar-prof" onclick="aprovarCadastro(${profissional.id})">Aprovar</button>
             `;
            rejeitadosList.appendChild(li);
        });
    } else {
        console.error(result.message);
        alert('Erro ao carregar dados.');
    }
});
async function reverterStatus(id, novoStatus) {
    try {
        // Envia a requisição para mudar o status no servidor
        const response = await fetch('/sifaps/backend/controllers/Admin/rejeitar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id, status: novoStatus })
        });

        // Verifica se a resposta foi bem-sucedida
        if (!response.ok) {
            console.error('Erro na requisição:', response.status, response.statusText);
            return;  // Sai da função se a requisição falhou
        }

        // Recebe a resposta em JSON
        const result = await response.json();
        console.log(result); // Verifique o que está sendo retornado da API

        alert(result.message);

        // Verifique se a alteração foi bem-sucedida
        if (result.status === 'success') {
            // Atualiza o status do usuário no localStorage
            const usuarioLogadoRaw = localStorage.getItem('usuario');
            
            if (usuarioLogadoRaw) {
                try {
                    const usuarioLogado = JSON.parse(usuarioLogadoRaw);
                    console.log(usuarioLogado); // Verifique os dados do usuário no localStorage

                    // Se o usuário for o mesmo que teve o status alterado, atualize o localStorage
                    if (usuarioLogado.id === id) {
                        usuarioLogado.status = novoStatus;  // Atualiza o status com o novo valor
                        localStorage.setItem('usuario', JSON.stringify(usuarioLogado));  // Salva o novo status no localStorage
                        console.log("Status atualizado no localStorage.");
                    }
                } catch (error) {
                    console.error("Erro ao atualizar status no localStorage:", error);
                }
            }

            // Opcional: atualize a interface do usuário ou recarregue a página
            location.reload();  // Pode recarregar a página para refletir as mudanças (se necessário)
        } else {
            console.error("Erro ao atualizar o status:", result.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao reverter status. Tente novamente.');
    }
}

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
                        
                        // Atualize o localStorage com os dados mais recentes
                        localStorage.setItem('usuario', JSON.stringify(usuarioAtualizado));

                        console.log("Dados atualizados no localStorage:", usuarioAtualizado);
                    }
                })
                .catch(error => console.error("Erro ao obter dados atualizados do servidor:", error));
            
        } catch (error) {
            console.error('Erro ao fazer o parse do JSON:', error);
        }
    }
});

// Função para rejeitar o usuário
function rejeitarCadastro(id) {
    fetch('/sifaps/backend/controllers/Admin/rejeitar.php', {  // Substitua o caminho da rota conforme necessário
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })  // Envia o ID do usuário para o backend
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log(data.message);
            
            // Atualize o status no localStorage, se necessário
            const usuarioLogadoRaw = localStorage.getItem('usuario');
            
            if (usuarioLogadoRaw) {
                try {
                    const usuarioLogado = JSON.parse(usuarioLogadoRaw);
                    
                    // Se o usuário rejeitado for o mesmo do localStorage, atualize o status
                    if (usuarioLogado.id === id) {
                        usuarioLogado.status = 'rejeitado';  // Atualiza o status
                        localStorage.setItem('usuario', JSON.stringify(usuarioLogado));  // Atualiza no localStorage
                        console.log("Status atualizado no localStorage."); 
                    }
                    // Opcional: atualize a interface do usuário ou recarregue a página
            location.reload(); 
                } catch (error) {
                    console.error("Erro ao atualizar status no localStorage:", error);
                }
            }

            // Atualize a UI (remover da tabela, ou outra ação que seja necessária)
            const row = document.getElementById(`user-row-${id}`);
            if (row) {
                row.remove();
            }
            
            // Ou você pode fazer algo como recarregar a página:
            // location.reload();  // Recarregar para refletir as mudanças
        } else {
            console.error(data.message);
        }
    })
    .catch(error => {
        console.error('Erro na requisição de rejeição:', error);
        alert('Erro ao tentar rejeitar o usuário. Tente novamente.');
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('/sifaps/backend/controllers/Admin/listarCadastro.php');
        const result = await response.json();

        if (result.status === 'success') {
            const tableBody = document.getElementById('profissionaisTable').querySelector('tbody');
            result.data.forEach(profissional => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${profissional.id}</td>
                    <td>${profissional.nome}</td>
                    <td>${profissional.email}</td>
                    <td>${profissional.celular}</td>
                    <td>${profissional.cpf}</td>
                    <td>${profissional.cidade}</td>
                    <td>${profissional.estado}</td>
                    <td>${profissional.crp_crm}</td>
                    <td>${profissional.profissao}</td>
                    <td>${formatarData(profissional.data_cadastro)}</td> 
                    <td>
                        <button class="btn approve" onclick="aprovarCadastro(${profissional.id})">Aprovar</button>
                        <button class="btn reject" onclick="rejeitarCadastro(${profissional.id})">Rejeitar</button>
                        <button class="btn delete" onclick="excluirCadastro(${profissional.id})">Excluir</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            console.error(result.message);
            alert('Erro ao carregar dados.');
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao buscar dados. Tente novamente.');
    }
});

function formatarData(dataString) {
    const data = new Date(dataString);
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0'); // Mes começa em 0
    const ano = data.getFullYear();
    
    return `${dia}/${mes}/${ano}`;
}

// Função para aprovar o usuário
function aprovarCadastro(id) {
fetch('/sifaps/backend/controllers/Admin/aprovar.php', {
method: 'POST',
headers: {
    'Content-Type': 'application/json'
},
body: JSON.stringify({ id: id })
})
.then(response => response.json())
.then(data => {
if (data.status === 'success') {
    console.log(data.message);
    
    // Atualize o status no localStorage
    const usuarioLogadoRaw = localStorage.getItem('usuario');
    
    if (usuarioLogadoRaw) {
        try {
            const usuarioLogado = JSON.parse(usuarioLogadoRaw);
            
            // Se o usuário aprovado for o mesmo do localStorage, atualize o status
            if (usuarioLogado.id === id) {
                usuarioLogado.status = 'aprovado';  // Atualiza o status
                localStorage.setItem('usuario', JSON.stringify(usuarioLogado));  // Atualiza no localStorage
                console.log("Status atualizado no localStorage.");
            }
            
        } catch (error) {
            console.error("Erro ao atualizar status no localStorage:", error);
        }
    }

    // Atualize a UI, se necessário
    location.reload();  // Opcional: recarrega a página para refletir as mudanças
} else {
    console.error(data.message);
}
})
.catch(error => console.error('Erro na requisição:', error));
}

function excluirCadastro(id) {
const confirmDelete = confirm("Você tem certeza que deseja excluir este usuário?");
if (!confirmDelete) return;

fetch('/sifaps/backend/controllers/Admin/excluir.php', {
method: 'POST',
headers: {
    'Content-Type': 'application/json'
},
body: JSON.stringify({ id: id })  // Envia o ID do usuário para o backend
})
.then(response => response.json())
.then(data => {
if (data.status === 'success') {
    console.log(data.message);
    alert('Usuário excluído com sucesso.');
    location.reload();
    // Remover a linha da tabela para refletir a exclusão
    const row = document.getElementById(`user-row-${id}`);
    if (row) {
        row.remove();
    }
} else {
    console.error(data.message);
    alert('Erro ao excluir o usuário.');
}
})
.catch(error => {
console.error('Erro na requisição de exclusão:', error);
alert('Erro ao tentar excluir o usuário. Tente novamente.');
});
}

async function alterarStatusUsuario(id, status) {
    try {
        const response = await fetch('/sifaps/backend/controllers/Admin/alterarStatusUsuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id, status }),
        });

        const result = await response.json();
        if (result.status === 'success') {
            alert('Status do usuário alterado com sucesso!');
            
            // Recarregar as tabelas após a alteração
            await carregarUsuariosAprovados();
            await carregarUsuariosRejeitados();

            // Reatribui os eventos após a atualização
            adicionarEventos();
        } else {
            alert('Erro ao alterar status do usuário: ' + result.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao alterar status do usuário.');
    }
}


// Função para adicionar eventos aos botões
function adicionarEventos() {
    // Remove todos os ouvintes antigos antes de adicionar novos
    document.querySelectorAll('.block-btn').forEach(button => {
        const newButton = button.cloneNode(true); // Clona o botão
        button.parentNode.replaceChild(newButton, button); // Substitui pelo clone
    });

    document.querySelectorAll('.unlock-btn').forEach(button => {
        const newButton = button.cloneNode(true); // Clona o botão
        button.parentNode.replaceChild(newButton, button); // Substitui pelo clone
    });

    // Adiciona eventos para os botões atualizados
    document.querySelectorAll('.block-btn').forEach(button => {
        button.addEventListener('click', async event => {
            const userId = event.target.getAttribute('data-id');
            if (confirm('Tem certeza que deseja bloquear este usuário?')) {
                await alterarStatusUsuario(userId, 'rejeitado');
            }
        });
    });

    document.querySelectorAll('.unlock-btn').forEach(button => {
        button.addEventListener('click', async event => {
            const userId = event.target.getAttribute('data-id');
            if (confirm('Tem certeza que deseja ativar este usuário?')) {
                await alterarStatusUsuario(userId, 'aprovado');
            }
        });
    });
}


document.addEventListener('DOMContentLoaded', async () => {
    await carregarUsuariosAprovados();
    await carregarUsuariosRejeitados();
});

async function carregarUsuariosAprovados() {
    try {
        const response = await fetch('/sifaps/backend/controllers/Admin/listarUser-aprovado.php');
        const result = await response.json();

        if (result.status === 'success') {
            const tableBody = document.getElementById('usuariosTable').querySelector('tbody');
            tableBody.innerHTML = ''; // Limpa a tabela antes de recarregar os dados
            result.data.forEach(usuario => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${usuario.id}</td>
                    <td>${usuario.nome}</td>
                    <td>${usuario.nome_usuario}</td>
                    <td>${usuario.email}</td>
                    <td>${usuario.celular}</td>
                    <td>${usuario.cpf}</td>
                    <td>${usuario.cidade}</td>
                    <td>${usuario.estado}</td>
                    <td>${formatarData(usuario.data_cadastro)}</td>
                    <td>
                        <button class="block-btn" data-id="${usuario.id}">Bloquear</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            adicionarEventos();
        } else {
            console.error(result.message);
            alert('Erro ao carregar usuários aprovados.');
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao carregar usuários aprovados.');
    }
}

async function carregarUsuariosRejeitados() {
    try {
        const response = await fetch('/sifaps/backend/controllers/Admin/listarUser-rejeitados.php');
        const result = await response.json();

        if (result.status === 'success') {
            const tableBody = document.getElementById('usuariosTable-regeitado').querySelector('tbody');
            tableBody.innerHTML = ''; // Limpa a tabela antes de recarregar os dados
            result.data.forEach(usuario => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${usuario.id}</td>
                    <td>${usuario.nome}</td>
                    <td>${usuario.nome_usuario}</td>
                    <td>${usuario.email}</td>
                    <td>${usuario.celular}</td>
                    <td>${usuario.cpf}</td>
                    <td>${usuario.cidade}</td>
                    <td>${usuario.estado}</td>
                    <td>${formatarData(usuario.data_cadastro)}</td>
                    <td>
                        <button class="unlock-btn" data-id="${usuario.id}">Ativar</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            adicionarEventos();
        } else {
            console.error(result.message);
            alert('Erro ao carregar usuários rejeitados.');
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao carregar usuários rejeitados.');
    }
}
