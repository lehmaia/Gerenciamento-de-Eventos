const guests = [
  {
    "name": "João Silva",
    "contact": "1234-5678",
    "inviteSent": true,
    "confirmedPresence": false,
    "editable": false
  },
  {
    "name": "Maria Oliveira",
    "contact": "9876-5432",
    "inviteSent": false,
    "confirmedPresence": true,
    "editable": false
  }
];

function renderTable(){

  const tbody = document.querySelector('#guests-table tbody');
  tbody.innerHTML = '';
  guests.forEach((guest, index) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>
        <input type="text" value="${guest.name}" ${guest.editable ? '' : 'disabled'} onchange="updateGuest(${index}, 'name', this.value)">
      </td>
      <td>
        <input type="text" value="${guest.contact}" ${guest.editable ? '' : 'disabled'} onchange="updateGuest(${index}, 'contact', this.value)">
      </td>
      <td>
        <input type="checkbox" ${guest.inviteSent ? 'checked' : ''} ${guest.editable ? '' : 'disabled'} onclick="toggleInviteSent(${index})">
      </td>
      <td>
        <input type="checkbox" ${guest.confirmedPresence ? 'checked' : ''} ${guest.editable ? '' : 'disabled'} onclick="togglePresenceConfirmed(${index})">
      </td>
      <td class="actions">
        <span class="action-btn" onclick="editGuest(${index})">${guest.editable ? '<i class="bx bx-save">' : '<i class="bx bx-pencil">'}</span>
        <span class="action-btn" onclick="deleteGuest(${index})"><i class="bx bx-x"></span>
      </td>`
    ;
    tbody.appendChild(row);
  });
}

renderTable();

document.getElementById('addButton').addEventListener('click', function() {
  document.getElementById('modal').style.display = 'flex';
});

document.getElementById('closeButton').addEventListener('click', function() {
  document.getElementById('modal').style.display = 'none';
});

window.addEventListener('click', function(event) {
  if (event.target === document.getElementById('modal')) {
    document.getElementById('modal').style.display = 'none';
  }
});

document.getElementById('guestForm').addEventListener('submit', function(event) {
  event.preventDefault();

  const name = document.getElementById('name').value;
  const contact = document.getElementById('contact').value;
  const invite = document.getElementById('invite').value === "true"; 
  const presence = document.getElementById('presence').value === "true"; 

  const newGuest = {
    name,
    contact,
    inviteSent: invite,
    confirmedPresence: presence,
    editable: false
  };

  guests.push(newGuest); 

  renderTable();

  document.getElementById('modal').style.display = 'none';
});

function editGuest(index) {
  guests[index].editable = !guests[index].editable; 
  renderTable();
}

function deleteGuest(index) {
  const confirmDelete = confirm("Tem certeza que deseja excluir este convidado?");
  if (confirmDelete) {
    guests.splice(index, 1); 
    renderTable(); 
  }
}

function updateGuest(index, field, value) {
  guests[index][field] = value; 
}

function toggleInviteSent(index) {
  guests[index].inviteSent = !guests[index].inviteSent;
}