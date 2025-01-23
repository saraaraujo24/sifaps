// Obter elementos
const openModalBtn = document.getElementById('openModalBtn');
const modal = document.getElementById('modal');
const closeModalBtn = document.getElementById('closeModalBtn');

// Abrir o modal
openModalBtn.addEventListener('click', () => {
    modal.style.display = 'block';
});

// Fechar o modal
closeModalBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

// Fechar o modal clicando fora dele
window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

// Caso o formulário precise ser enviado (você pode modificar isso conforme necessário)
const loginForm = document.getElementById('loginForm');
loginForm.addEventListener('submit', (event) => {
    event.preventDefault(); // Evita o envio do formulário
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Aqui você pode fazer a lógica de autenticação, como enviar os dados via AJAX ou validações

    console.log('Email:', email);
    console.log('Senha:', password);

    // Fechar o modal após o envio
    modal.style.display = 'none';
});

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Impede o envio tradicional do formulário
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Mostrar os dados no console antes de enviar
    console.log('Dados enviados:', { email: email, password: password });

    // Envia os dados para o backend via AJAX
    fetch('/sifaps/backend/controllers/LogarAdmin/login-admin.php', {
        method: 'POST',
        body: JSON.stringify({ email: email, password: password }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Login bem-sucedido
            alert('Login bem-sucedido!');
            window.location.href = '../Admin/index.html'; // Redireciona para uma página após login
        } else {
            // Login falhou
            alert('Credenciais inválidas!');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao realizar login!');
    });
});


