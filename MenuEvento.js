let currentList = '';

function getIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id_evento'); // Pega o valor de "id_evento" na URL
}

// Função chamada quando o formulário de adicionar cartão for mostrado
function showAddCardForm(listId) {
    currentList = listId;
    document.getElementById('add-card-form').style.display = 'block'; // Exibe o formulário
}

// Função para fechar o formulário de adicionar cartão
function closeAddCardForm() {
    document.getElementById('add-card-form').style.display = 'none';
    document.getElementById('card-text').value = ''; // Limpa o campo de texto
}

// Função para excluir um cartão
function deleteCard(cardId) {
    if (confirm('Tem certeza de que deseja excluir este cartão?')) {
        const cardNumericId = cardId.split('-')[1]; // Obtém o número após 'card-'

        // Envia a solicitação DELETE para o PHP com o ID no corpo da requisição
        fetch('delete_card.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${cardNumericId}` // Envia o ID no corpo da requisição
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cartão excluído com sucesso!');
                document.getElementById(cardId).remove(); // Remove o cartão do DOM
            } else {
                alert(`Erro ao excluir o cartão: ${data.error}`);
            }
        })
        .catch(error => console.error('Erro ao excluir o cartão:', error));
    }
}


// Função para carregar cartões ao carregar a página
function loadCards() {
    const eventoId = getIdFromUrl();
    fetch('get_cards.php?evento_id=' + eventoId)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                data.forEach(card => {
                    const cardElement = document.createElement('div');
                    cardElement.classList.add('card');
                    cardElement.id = 'card-' + card.id;

                    // Adiciona o texto e o botão de exclusão
                    cardElement.innerHTML = `
                        <span>${card.text}</span>
                        <button class="delete-card" onclick="deleteCard(${card.id})">Excluir</button>
                    `;

                    cardElement.setAttribute("draggable", "true");
                    cardElement.setAttribute("ondragstart", "drag(event)");

                    // Adiciona o cartão à lista correta no DOM
                    const listElement = document.getElementById(getListIdByHtmlId(card.list_id));
                    if (listElement) {
                        const cardList = listElement.querySelector('.card-list');
                        if (cardList) {
                            cardList.appendChild(cardElement);
                        }
                    }
                });
            } else {
                console.error("A resposta não é um array:", data);
            }
        })
        .catch(error => console.error('Erro ao carregar os cartões:', error));
    }

    // Funções para arrastar e soltar
    function allowDrop(ev) {
        ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();

    const cardId = ev.dataTransfer.getData("text");
    const draggedCard = document.getElementById(cardId);
    const targetList = ev.target.closest('.card-list');
    
    if (targetList) {
        targetList.appendChild(draggedCard); // Adiciona o cartão visualmente na nova lista

        const listId = getListIdByHtmlId(targetList.id);
        const eventoId = getIdFromUrl();

        fetch('Card.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `cardId=${cardId.split('-')[1]}&listId=${listId}&evento_id=${eventoId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erro ao atualizar o cartão:', data.error);
            } else {
                console.log('Cartão movido com sucesso!');
            }
        })
        .catch(error => console.error('Erro ao mover o cartão:', error));
    }
}

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

// Função para adicionar um novo cartão
function addCard() {
    const cardText = document.getElementById('card-text').value;
    const listId = currentList === 'todo' ? 1 : currentList === 'in-progress' ? 2 : 3;
    const eventoId = getIdFromUrl();  // Pega o evento_id da URL
    
    if (cardText.trim() !== '') {
        fetch('Card.php?evento_id=' + eventoId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `cardText=${encodeURIComponent(cardText)}&listId=${listId}&evento_id=${eventoId}`  // Envia o evento_id junto com os dados do cartão
        })
        .then(response => response.text())  // Mudando para text() para ver o que o servidor retorna
        .then(text => {
            console.log('Resposta do servidor:', text);  // Exibe o conteúdo da resposta
            try {
                const data = JSON.parse(text);  // Tenta analisar como JSON
                if (data.cardId) {
                    const card = document.createElement('div');
                    card.classList.add('card');
                    card.id = 'card-' + data.cardId;
                    card.textContent = data.text;
                    card.setAttribute('draggable', 'true');
                    card.setAttribute('ondragstart', 'dragStart(event)');

                    // Adiciona o texto e o botão de exclusão
                    card.innerHTML = `<span>${data.text}</span>
                    <button class="delete-card" onclick="deleteCard('card-${data.cardId}')"><i class="bx bx-x"></i></button>`;  
    
                    document.getElementById(currentList + '-list').appendChild(card);
                    closeAddCardForm();
                } else {
                    console.error('Erro ao adicionar o cartão:', data.error);
                }
            } catch (e) {
                console.error('Erro ao analisar JSON:', e, text);  // Exibe erro ao tentar converter para JSON
            }
        })
        .catch(error => {
            console.error('Erro na solicitação:', error);  // Exibe o erro de solicitação
            alert('Erro ao adicionar o cartão. Tente novamente.');
        });
    }
}