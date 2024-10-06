/*document.querySelector('.formLogin').addEventListener('submit', async (e) => {
    e.preventDefault(); // Evita o envio padrão do formulário

    const formData = new FormData(e.target);

    try {
        const response = await fetch('/sifaps/backend/controllers/Login/login.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.statusText}`);
        }

        const contentType = response.headers.get('Content-Type');
        if (contentType && contentType.includes('application/json')) {
            const result = await response.json(); // Espera-se que o backend retorne JSON
            console.log('Resposta do servidor:', result);

            if (result.status === 'success') {
                console.log('Login bem-sucedido. Redirecionando...');

                
                window.location.href = '../Comentario/index.html';
            } else {
                alert(`Erro: ${result.message}`); // Exibe mensagem de erro
            }
        } else {
            // Se não for JSON, trate como erro
            const text = await response.text();
            console.error('Expected JSON but got:', text);
        }
    } catch (error) {
        console.error('Error:', error);
    }
});*/

/*document.querySelector('.formLogin').addEventListener('submit', async (e) => {
    e.preventDefault(); // Evita o envio padrão do formulário

    const formData = new FormData(e.target);

    try {
        const response = await fetch('/sifaps/backend/controllers/Login/login.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.statusText}`);
        }

        const contentType = response.headers.get('Content-Type');
        if (contentType && contentType.includes('application/json')) {
            const result = await response.json(); // Espera-se que o backend retorne JSON
            console.log('Resposta do servidor:', result);

            if (result.status === 'success') {
                console.log('Login bem-sucedido. Redirecionando...');

                // Armazenar dados do usuário no localStorage
                localStorage.setItem('usuario', JSON.stringify(result.data)); // Supondo que result.data contenha as informações do usuário
                console.log(result.data); // Verifica o que está sendo armazenado
                setTimeout(() => {
                    window.location.href = '../Comentario/index.html';
                }, 1000);
                //window.location.href = '../Comentario/index.html';
            } else {
                alert(`Erro: ${result.message}`); // Exibe mensagem de erro
            }
        } else {
            // Se não for JSON, trate como erro
            const text = await response.text();
            console.error('Expected JSON but got:', text);
        }
    } catch (error) {
        console.error('Error:', error);
    }
});*/
document.querySelector('.formLogin').addEventListener('submit', async (e) => {
    e.preventDefault(); // Evita o envio padrão do formulário

    const formData = new FormData(e.target);

    try {
        const response = await fetch('/sifaps/backend/controllers/Login/login.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.statusText}`);
        }

        const contentType = response.headers.get('Content-Type');
        if (contentType && contentType.includes('application/json')) {
            const result = await response.json(); // Espera-se que o backend retorne JSON

            if (result.status === 'success') {
                // Armazenar dados do usuário no localStorage
                localStorage.setItem('usuario', JSON.stringify(result.data)); // Supondo que result.data contenha as informações do usuário
                
                // Exibir o usuário no console
                console.log("Usuário logado:", JSON.parse(localStorage.getItem('usuario')));
                
                localStorage.setItem('cameFromSpecificRoute', 'true'); // Armazena a flag
                // Redirecionar
                setTimeout(() => {
                    window.location.href = '../comentarios/index.php';
                }, 1000);
            } else {
                alert(`Erro: ${result.message}`); // Exibe mensagem de erro
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
});




