document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('/sifaps/backend/controllers/Admin/listarRetor.php');
    const result = await response.json();

    if (result.status === 'success') {
        const aprovadosList = document.getElementById('aprovadosList');
        const rejeitadosList = document.getElementById('rejeitadosList');

        result.data.aprovados.forEach(profissional => {
            const li = document.createElement('li');
            li.textContent = profissional.nome; // Adicione outros dados conforme necessário
            aprovadosList.appendChild(li);
        });

        result.data.rejeitados.forEach(profissional => {
            const li = document.createElement('li');
            li.textContent = profissional.nome; // Adicione outros dados conforme necessário
            rejeitadosList.appendChild(li);
        });
    } else {
        console.error(result.message);
        alert('Erro ao carregar dados.');
    }
});