const menuToggle = document.getElementById('menuToggle');
const closeMenu = document.getElementById('closeMenu');
const menuList = document.querySelector('.ul');

menuToggle.addEventListener('click', () => {
    menuList.classList.toggle('ativo');
    closeMenu.style.display = 'block'; // Mostrar o ícone de fechar
});

closeMenu.addEventListener('click', () => {
    menuList.classList.remove('ativo');
    closeMenu.style.display = 'none'; // Esconder o ícone de fechar
});
