let currentList = '';

function getIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id_evento');  // Pega o valor de "id_evento" na URL
}

// Função chamada quando o formulário de adicionar cartão for mostrado
function showAddCardForm(listId) {
    currentList = listId;
    document.getElementById('add-card-form').style.display = 'block';
}

// Função para fechar o formulário de adicionar cartão
function closeAddCardForm() {
    document.getElementById('add-card-form').style.display = 'none';
}

// Função para adicionar o cartão na lista
function addCard() {
    const cardText = document.getElementById('card-text').value.trim(); // Pega o texto do cartão e remove espaços desnecessários
    const listId = currentList === 'todo' ? 1 : currentList === 'in-progress' ? 2 : 3; // Determina o listId com base na lista atual
    const eventoId = getIdFromUrl(); // Pega o evento_id da URL

    if (cardText !== '') { // Apenas continua se o texto não estiver vazio
        fetch('Card.php?evento_id=' + eventoId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `cardText=${encodeURIComponent(cardText)}&listId=${listId}&evento_id=${eventoId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erro ao adicionar o cartão:', data.error);
            } else {
                // Adiciona o cartão ao DOM
                const card = document.createElement('div');
                card.classList.add('card');
                card.id = 'card-' + data.cardId;
                card.textContent = data.text;
                card.setAttribute('draggable', 'true');
                card.setAttribute('ondragstart', 'drag(event)');

                // Adiciona o cartão à lista correta no DOM
                document.getElementById(currentList + '-list').appendChild(card);

                // Fecha o formulário e limpa o campo de texto
                closeAddCardForm();
                document.getElementById('card-text').value = ''; // Limpa o campo de texto
            }
        })
        .catch(error => console.error('Erro na solicitação:', error));
    } else {
        alert('O texto do cartão não pode estar vazio!');
    }
}

function addCard() {
    const cardText = document.getElementById('card-text').value;
    if (cardText.trim() !== '') {
        const card = document.createElement('div');
        card.classList.add('card');
        card.textContent = cardText;
        document.getElementById(currentList).querySelector('.card-list').appendChild(card);
        closeAddCardForm();
    }
}


// Função para mapear o ID da lista HTML para o ID numérico
function getListIdByNumber(listName) {
    switch (listName) {
        case 'todo':
            return 1;
        case 'in-progress':
            return 2;
        case 'done':
            return 3;
        default:
            return 0;  // Valor padrão, se não encontrado
    }
}

// Função para permitir que o item seja arrastado
function allowDrop(ev) {
    ev.preventDefault();
}

// Função chamada quando o cartão começa a ser arrastado
function drag(ev) {
    // Guardando o id do cartão sendo arrastado
    ev.dataTransfer.setData("text", ev.target.id);
}

// Função chamada quando o cartão é solto em uma nova lista
function drop(ev) {
    ev.preventDefault();

    const cardId = ev.dataTransfer.getData("text"); // Pega o ID do cartão arrastado
    const draggedCard = document.getElementById(cardId);

    const targetList = ev.target.closest('.card-list'); // Verifica onde o cartão foi solto
    if (targetList) {
        targetList.appendChild(draggedCard); // Adiciona o cartão visualmente na nova lista

        const listId = getListIdByHtmlId(targetList.id); // Determina o novo list_id
        const eventoId = getIdFromUrl(); // Obtém o evento_id da URL

        // Monta os dados da requisição
        const body = cardId
            ? `cardId=${cardId.split('-')[1]}&listId=${listId}&evento_id=${eventoId}` // Atualização
            : `cardText=${encodeURIComponent(draggedCard.textContent)}&listId=${listId}&evento_id=${eventoId}`; // Adição

        console.log('Dados enviados:', body);

        // Faz a requisição para atualizar ou adicionar o cartão no banco
        fetch('Card.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro ao atualizar ou adicionar o cartão:', data.error);
                } else {
                    console.log('Resposta do servidor:', data);
                    if (!cardId && data.cardId) {
                        // Se é um novo cartão, atualiza o ID do cartão no DOM
                        draggedCard.id = 'card-' + data.cardId;
                    }
                }
            })
            .catch(error => console.error('Erro na solicitação:', error));
    }
}


// Função para converter o número da lista em ID da lista HTML
function getListIdByHtmlId(listId) {
    switch (listId) {
        case 'todo-list':
            return 1;
        case 'in-progress-list':
            return 2;
        case 'done-list':
            return 3;
        default:
            return 0;
    }
}

document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('dragstart', drag);
    card.addEventListener('dragend', dragEnd);
});

document.querySelectorAll('.card-list').forEach(list => {
    list.addEventListener('dragover', allowDrop);
    list.addEventListener('drop', drop);
});

// Função chamada quando o cartão é solto em uma nova lista
function dragEnd(e) {
    e.target.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    loadCards();
});

function loadCards() {
    const eventoId = getIdFromUrl();
    fetch('get_cards.php?evento_id=' + eventoId)
        .then(response => response.json())
        .then(data => {
            console.log('Resposta do servidor:', data);
            if (Array.isArray(data)) {
                const filteredCards = data.filter(card => card.evento_id == eventoId);
                filteredCards.forEach(card => {
                    const cardElement = document.createElement('div');
                    cardElement.classList.add('card');
                    cardElement.id = 'card-' + card.id;
                    cardElement.textContent = card.text;
                    cardElement.setAttribute("draggable", "true");
                    cardElement.setAttribute("ondragstart", "drag(event)");

                    const listId = getListIdByNumber(card.newListId);
                    const listElement = document.getElementById(listId);
                    if (listElement) {
                        listElement.querySelector('.card-list').appendChild(cardElement);
                    }
                });
            } else {
                console.error("A resposta não é um array:", data);
            }
        })
        .catch(error => console.error('Erro ao carregar os cartões:', error));
}
