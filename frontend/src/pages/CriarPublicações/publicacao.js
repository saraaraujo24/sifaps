
document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('usuario'));
    console.log(user);


    // Verificar se há um usuário logado
    if (user) {
   
        // Adicionar evento de clique para limpar o localStorage
        linkSair.addEventListener('click', () => {
            localStorage.clear(); // Limpa o localStorage
        });

        liSair.appendChild(linkSair);
        menuList.appendChild(liSair); // Adiciona o botão "Sair" ao final do menu
    }

    // Condicional para usuários do tipo "profissional"
    if (user) {
      
    }
});



document.addEventListener('DOMContentLoaded', () => {
    console.log('Página carregada');
});

function mostrarDadosUsuario() {
    // Recuperando os dados do usuário do localStorage
    let usuarioString = localStorage.getItem('usuario');
    console.log(usuarioString)
    if (usuarioString) {
        let usuario = JSON.parse(usuarioString);
        
     
        // Passar o nome do usuário e CRP/CRM para o formulário (campos ocultos)
        let nomeInput = document.createElement('input');
        nomeInput.type = 'hidden';
        nomeInput.name = 'nome';
        nomeInput.value = usuario.nome;
        document.forms[0].appendChild(nomeInput); // Adiciona o campo nome ao formulário

        let crpInput = document.createElement('input');
        crpInput.type = 'hidden';
        crpInput.name = 'crp_crm';
        crpInput.value = usuario.crp_crm;
        document.forms[0].appendChild(crpInput); // Adiciona o campo crp_crm ao formulário
    } else {
        // Caso não haja dados no localStorage
        document.getElementById('output').textContent = 'Usuário não logado.';
    }
}

// Chama a função para mostrar os dados do usuário quando a página carregar
window.onload = mostrarDadosUsuario;
