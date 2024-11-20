// Seleção de elementos
const daysContainer = document.getElementById('days-container');
const mainContainer = document.getElementById('main-container');
const currentMonth = document.getElementById('current-month');
const calendarIcon = document.getElementById('calendar-icon');
const addIcon = document.getElementById('add-icon');
const openAgendamento =document.getElementById('idmodal');
const fullCalendar = document.getElementById('full-calendar');
const closeCalendarBtn = document.getElementById('close-calendar');
const calendarMonth = document.getElementById('calendar-month');
const calendarDays = document.getElementById('calendar-days');
const prevMonthBtn = document.getElementById('prev-month');
const nextMonthBtn = document.getElementById('next-month');
const prevDayBtn = document.getElementById('prev-day');
const nextDayBtn = document.getElementById('next-day');

let selectedDate = new Date();

function getIdsFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id'); // Pega o valor do parâmetro "id"
    const idEvento = urlParams.get('id_evento'); // Pega o valor do parâmetro "id_evento"
    return { id, idEvento };
}

// Função para formatar a data (dd.mm.yyyy)
function formatDate(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}.${month}.${year}`; // Formato dd.mm.yyyy
}



// Função para carregar o calendário mensal
function loadMonthCalendar(date) {
    const currentYear = date.getFullYear();
    const currentMonthIndex = date.getMonth();
    const monthNames = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
    calendarMonth.textContent = `${monthNames[currentMonthIndex]} ${currentYear}`;

    calendarDays.innerHTML = '';
    const firstDayOfMonth = new Date(currentYear, currentMonthIndex, 1).getDay();
    const lastDayOfMonth = new Date(currentYear, currentMonthIndex + 1, 0).getDate();

    // Dias em branco antes do início do mês
    for (let i = 0; i < firstDayOfMonth; i++) {
        calendarDays.innerHTML += '<span></span>';
    }

    // Preencher dias do mês
    for (let day = 1; day <= lastDayOfMonth; day++) {
        const dayButton = document.createElement('button');
        dayButton.textContent = day;

        // Marcar o dia atual
        if (day === new Date().getDate() && currentMonthIndex === new Date().getMonth() && currentYear === new Date().getFullYear()) {
            dayButton.classList.add('selected');
        }

        dayButton.onclick = () => {
            selectedDate = new Date(currentYear, currentMonthIndex, day);
            loadDays();
            fullCalendar.style.display = 'none';
        };

        calendarDays.appendChild(dayButton);
    }
}

// Função para carregar os próximos 7 dias
function loadDays() {
    daysContainer.innerHTML = '';
    for (let i = 0; i < 7; i++) {
        const date = new Date(selectedDate);
        date.setDate(selectedDate.getDate() + i);

        const dayCard = document.createElement('div');
        dayCard.className = 'day-card';
        dayCard.textContent = formatDate(date);

        if (i === 0) dayCard.classList.add('selected');
        dayCard.onclick = () => selectDay(dayCard, date);

        daysContainer.appendChild(dayCard);
    }

}

// Função para selecionar um dia
function selectDay(dayCard, date) {
    document.querySelectorAll('.day-card').forEach(card => card.classList.remove('selected'));
    dayCard.classList.add('selected');
    selectedDate = date;
    loadAppointmentsForselectedDate();
}

// Função para carregar os agendamentos para a data selecionada
function loadAgendamentos() {
    const { id, idEvento } = getIdsFromUrl(); // Obtém os IDs da URL atual

    // Verifique se os parâmetros id e id_evento existem antes de prosseguir
    if (!id || !idEvento) {
        alert("ID do usuário ou ID do evento inválido.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", `Agenda.php?id=${id}&id_evento=${idEvento}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Enviar a data selecionada como parâmetro (garantindo o formato correto)
    xhr.send("selectedDate=" + formatDate(selectedDate));

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Atualizar o container de eventos com a resposta do PHP
            mainContainer.innerHTML = xhr.responseText;
        }
    };
}


function selectDay(dayCard, date) {
    document.querySelectorAll('.day-card').forEach(card => card.classList.remove('selected'));
    dayCard.classList.add('selected');
    selectedDate = date;
    loadAgendamentos(); // Atualizar os agendamentos para a data selecionada
}


// Navegação entre meses
prevMonthBtn.onclick = () => {
    selectedDate.setMonth(selectedDate.getMonth() - 1);
    loadMonthCalendar(selectedDate);
};
nextMonthBtn.onclick = () => {
    selectedDate.setMonth(selectedDate.getMonth() + 1);
    loadMonthCalendar(selectedDate);
};

// Navegação entre dias
prevDayBtn.onclick = () => {
    selectedDate.setDate(selectedDate.getDate() - 1);
    loadDays();
};
nextDayBtn.onclick = () => {
    selectedDate.setDate(selectedDate.getDate() + 1);
    loadDays();
};

// Abrir e fechar o calendário
calendarIcon.onclick = () => {
    loadMonthCalendar(selectedDate);
    fullCalendar.style.display = 'flex';
};
closeCalendarBtn.onclick = () => fullCalendar.style.display = 'none';

// Abrir modal de agendamento
addIcon.onclick = () => openAgendamento.style.display = 'flex';

// Inicializar calendário e lista de dias
loadDays();