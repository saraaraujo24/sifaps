document.querySelector('.formLogin').addEventListener('submit', async (e) => {
    e.preventDefault(); // Evita o envio padrão do formulário

    const formData = new FormData(e.target);
    const isProfessional = document.getElementById('userTypeProfessional').checked; // Verifica se o usuário marcou o checkbox de 'profissional'

    let isValid = true;

    // Validação dos campos
    if (isProfessional) {
        // Se for profissional, valida se o campo CRP/CRM e senha estão preenchidos
        if (!formData.get('crp_crm') || !formData.get('senha')) {
            alert('Os campos CRP/CRM e senha são obrigatórios para profissionais.');
            isValid = false;
        }
    } else {
        // Se não for profissional, valida se o campo de email e senha estão preenchidos
        if (!formData.get('email') || !formData.get('senha')) {
            alert('Os campos email e senha são obrigatórios para usuários comuns.');
            isValid = false;
        }
    }

    if (!isValid) return; // Se a validação falhar, interrompe o envio do formulário

    try {
        // Envia os dados do formulário para o backend
        const response = await fetch('/sifaps/backend/controllers/Login/login.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.statusText}`);
        }

        // Verifica o tipo de conteúdo da resposta
        const contentType = response.headers.get('Content-Type');
        if (contentType && contentType.includes('application/json')) {
            const result = await response.json(); // Espera-se que o backend retorne um JSON

            if (result.status === 'success') {
                // Armazenar dados do usuário no localStorage
                localStorage.setItem('usuario', JSON.stringify(result.data)); // Supondo que result.data contenha as informações do usuário
                
                // Exibir o usuário no console
                console.log("Usuário logado:", JSON.parse(localStorage.getItem('usuario')));
                localStorage.setItem('cameFromSpecificRoute', 'true'); // Armazena a flag
                // Redirecionar para a página de perfil
                setTimeout(() => {
                    window.location.href = '../Home/index.html';
                }, 1000);
            } else {
                alert(`Erro: ${result.message}`); // Exibe a mensagem de erro retornada pelo backend
            }
        }
    } catch (error) {
        console.error('Error:', error); // Exibe o erro se a requisição falhar
    }
});





