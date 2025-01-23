/*document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('usuario'));
    console.log(user);

    const menuList = document.getElementById('menu-list');
    // Verifica se o item "Criar Publicações" existe no menu
 

    // Verificar se há um usuário logado antes de adicionar o botão "Sair"
    if (user) {
        // Sempre adicionar o botão "Sair" se houver um usuário logado
        const liSair = document.createElement('li');
        const linkSair = document.createElement('a');
        linkSair.href = "../Home/index.html"; // Redireciona para a página inicial
        linkSair.textContent = "Sair";

        // Adicionar evento de clique para limpar o localStorage
        linkSair.addEventListener('click', () => {
            localStorage.clear(); // Limpa o localStorage
        });

        liSair.appendChild(linkSair);
        menuList.appendChild(liSair); // Adiciona o botão "Sair" ao final do menu
    }

    // Condicional para usuários do tipo "profissional"
    // Verificar se o usuário logado é um profissional
    if (user && user.tipo_usuario === 'profissional') {
        // Localizar o elemento "Profissionais" no menu
        const profissionaisItem = document.querySelector('li a[href="../profissional/profissional.html"]').parentElement;

        // Criar o item "Área do Profissional"
        const liPerfil = document.createElement('li');
        liPerfil.innerHTML = '<a href="../Perfil/perfil.html">Perfil</a>';

        // Inserir o novo item após o item "Profissionais"
        profissionaisItem.parentElement.insertBefore(liPerfil, profissionaisItem.nextSibling);
    }


});
*/

document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('usuario'));
    console.log(user);

    const menuList = document.getElementById('menu-list');

    // Verificar se há um usuário logado
    if (user) {
   
        // Sempre adicionar o botão "Sair" se houver um usuário logado
        const liSair = document.createElement('li');
        const linkSair = document.createElement('a');
        linkSair.href = "../Home/index.html"; // Redireciona para a página inicial
        linkSair.textContent = "Sair";

        // Adicionar evento de clique para limpar o localStorage
        linkSair.addEventListener('click', () => {
            localStorage.clear(); // Limpa o localStorage
        });

        liSair.appendChild(linkSair);
        menuList.appendChild(liSair); // Adiciona o botão "Sair" ao final do menu
    }

    // Condicional para usuários do tipo "profissional"
    if (user) {           
        // Localizar o elemento "Profissionais" no menu
        const profissionaisItem = document.querySelector('li a[href="../meu_projeto/visualizar.php"]').parentElement;

        // Criar o item "Área do Profissional"
        const liProfissional = document.createElement('li');
        liProfissional.innerHTML = '<a href="../Perfil/perfil.html">Perfil</a>';

        // Inserir o novo item após o item "Profissionais"
        profissionaisItem.parentElement.insertBefore(liProfissional, profissionaisItem.nextSibling);
    }
});
