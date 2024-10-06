let listaDeCidades = []; // Variável para armazenar todas as cidades

// Função que busca os estados do Brasil
function buscarEstados() {
    const estadoInput = document.getElementById('estado').value.toLowerCase();
    const autocompleteContainer = document.getElementById('autocomplete-container');
    autocompleteContainer.innerHTML = ''; // Limpar sugestões anteriores

    const urlEstados = 'https://brasilapi.com.br/api/ibge/uf/v1';

    fetch(urlEstados)
        .then(response => response.json())
        .then(data => {
            filtrarEstados(data, estadoInput);
        })
        .catch(error => console.error("Erro ao buscar estados:", error));
}

// Função que filtra os estados de acordo com o que foi digitado
function filtrarEstados(estados, estadoInput) {
    const autocompleteContainer = document.getElementById('autocomplete-container');
    const resultados = estados.filter(estado => estado.nome.toLowerCase().includes(estadoInput));

    autocompleteContainer.innerHTML = ''; // Limpar sugestões anteriores

    resultados.slice(0, 10).forEach(estado => {
        const estadoItem = document.createElement('div');
        estadoItem.classList.add('autocomplete-item');
        estadoItem.innerText = estado.nome;

        estadoItem.addEventListener('click', () => {
            selecionarEstado(estado);
        });

        autocompleteContainer.appendChild(estadoItem);
    });
}

// Função para selecionar o estado e buscar as cidades
function selecionarEstado(estado) {
    document.getElementById('estado').value = estado.nome;
    document.getElementById('autocomplete-container').innerHTML = '';
    buscarCidadesPorEstado(estado.sigla); // Aqui utilizamos a sigla do estado (ex: SP, RJ)
}

// Função que busca as cidades de acordo com o estado selecionado
function buscarCidadesPorEstado(uf) {
    const urlCidades = `https://brasilapi.com.br/api/ibge/municipios/v1/${uf}`;

    fetch(urlCidades)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro ao buscar cidades: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            listaDeCidades = data; // Armazenar a lista de cidades
            mostrarCidades(listaDeCidades); // Chamar a função para mostrar cidades
        })
        .catch(error => console.error("Erro ao buscar cidades:", error));
}

// Função que mostra as cidades no select
function mostrarCidades(cidades) {
    const selectCidade = document.getElementById('cidade');
    selectCidade.innerHTML = ''; // Limpa cidades anteriores
    selectCidade.innerHTML += '<option value="">Selecione uma cidade</option>'; // Opção padrão

    if (!Array.isArray(cidades)) {
        console.error("Cidades não é um array:", cidades);
        return;
    }

    if (cidades.length === 0) {
        console.warn("Nenhuma cidade encontrada para o estado selecionado.");
        return;
    }

    cidades.forEach(cidade => {
        const option = document.createElement('option');
        option.value = cidade.nome; // Pode ser o id ou o nome, dependendo do que você precisa
        option.innerText = cidade.nome; // Nome da cidade
        selectCidade.appendChild(option);
    });
}

// Função para selecionar a cidade e atualizar a exibição
function selecionarCidade(selectElement) {
    const cidadeNome = selectElement.options[selectElement.selectedIndex].text;
    console.log(`Cidade selecionada: ${cidadeNome}`);
}
