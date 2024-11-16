let currentList = '';

function showAddCardForm(listId) {
    currentList = listId;
    document.getElementById('add-card-form').style.display = 'block';
}

function closeAddCardForm() {
    document.getElementById('add-card-form').style.display = 'none';
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

document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('dragstart', dragStart);
    card.addEventListener('dragend', dragEnd);
});

document.querySelectorAll('.card-list').forEach(list => {
    list.addEventListener('dragover', dragOver);
    list.addEventListener('drop', dropCard);
});

function dragStart(e) {
    e.dataTransfer.setData('text/plain', e.target.id);
    setTimeout(() => e.target.classList.add('hidden'), 0);
}

function dragEnd(e) {
    e.target.classList.remove('hidden');
}

function dragOver(e) {
    e.preventDefault();
}

function dropCard(e) {
    e.preventDefault();
    const cardId = e.dataTransfer.getData('text/plain');
    const card = document.getElementById(cardId);
    const targetList = e.target.closest('.card-list');

    if (targetList && card) {
        targetList.appendChild(card);

        // Obter o novo ID da lista
        const newListId = targetList.id.split('-')[0];

        // Enviar a atualização para o servidor
        fetch('Card.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `cardId=${cardId.split('-')[1]}&listId=${newListId}`
        }).then(response => response.text())
          .then(data => console.log(data))
          .catch(err => console.error('Erro:', err));
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

    const cardId = ev.dataTransfer.getData("text");
    const draggedCard = document.getElementById(cardId);

    const targetList = ev.target.closest('.card-list');
    if (targetList) {
        targetList.appendChild(draggedCard); // Adiciona o cartão na nova lista

        // Determine o novo list_id com base na lista
        const listId = getListIdByHtmlId(targetList.id);

        // Atualiza o list_id no banco de dados
        fetch('Card.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `cardId=${cardId.replace('card-', '')}&listId=${listId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erro ao atualizar o cartão:', data.error);
            }
        })
        .catch(error => console.error('Erro na atualização do cartão:', error));
    }
}

// Função para mapear o ID da lista HTML para o ID numérico
function getListIdByHtmlId(htmlId) {
    switch (htmlId) {
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


// Função para abrir o formulário de adicionar um novo cartão
function showAddCardForm(listId) {
    currentList = listId;
    document.getElementById('add-card-form').style.display = 'block';
}

// Função para fechar o formulário de adicionar cartão
function closeAddCardForm() {
    document.getElementById('add-card-form').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    loadCards();
});

function loadCards() {
    fetch('get_cards.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(card => {
                // Cria o elemento de cartão
                const cardElement = document.createElement('div');
                cardElement.classList.add('card');
                cardElement.id = 'card-' + card.id;
                cardElement.textContent = card.text;
                cardElement.setAttribute("draggable", "true");
                cardElement.setAttribute("ondragstart", "drag(event)");

                // Adiciona o cartão à lista correspondente
                const listId = card.list_id;
                const listElement = document.getElementById(getListIdByNumber(listId));
                if (listElement) {
                    listElement.querySelector('.card-list').appendChild(cardElement);
                }
            });
        })
        .catch(error => console.error('Erro ao carregar os cartões:', error));
}

// Função para converter o número da lista em ID da lista HTML
function getListIdByNumber(listId) {
    switch (listId) {
        case 1:
            return 'todo-list';
        case 2:
            return 'in-progress-list';
        case 3:
            return 'done-list';
        default:
            return '';
    }
}

function addCard() {
    const cardText = document.getElementById('card-text').value;
    const listId = currentList === 'todo' ? 1 : currentList === 'in-progress' ? 2 : 3;

    if (cardText.trim() !== '') {
        fetch('Card.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `cardText=${encodeURIComponent(cardText)}&listId=${listId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.cardId) {
                const card = document.createElement('div');
                card.classList.add('card');
                card.id = 'card-' + data.cardId;
                card.textContent = data.text;
                card.setAttribute("draggable", "true");
                card.setAttribute("ondragstart", "drag(event)");

                document.getElementById(currentList + '-list').appendChild(card);
                closeAddCardForm();
            } else {
                console.error('Erro ao adicionar o cartão:', data.error);
            }
        })
        .catch(error => console.error('Erro na solicitação:', error));
    }
}