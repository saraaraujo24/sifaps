document.addEventListener('DOMContentLoaded', () => {
    // Recupera o usuário armazenado no localStorage
    const user = JSON.parse(localStorage.getItem('usuario'));

    // Verifica se o menu existe
    const menuList = document.getElementById('menu-list');

    // Verifica se o item "Criar Publicações" existe no menu
    const criarPublicacoesItem = menuList.querySelector('li a[href="../CriarPublicações/index.php"]').parentElement;
 // Verifica se o item "Criar Publicações" existe no menu
    const criarPerfilItem = menuList.querySelector('li a[href="../meu_projeto/index.html"]').parentElement;
    // Verifica se o tipo do usuário é "usuario"
    if (user && user.tipo_usuario === 'usuario') {
        // Se o usuário for do tipo "usuario", oculta ou remove o item de "Criar Publicações"
        if (criarPublicacoesItem) {
            criarPublicacoesItem.style.display = 'none';  // Oculta o item do menu
            criarPerfilItem.style.display = 'none';  // Oculta o item do menu
            // Ou use criarPublicacoesItem.remove(); para removê-lo completamente
        }
    }
});
