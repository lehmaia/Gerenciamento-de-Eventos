document.getElementById("addButton").addEventListener("click", function () {
    document.getElementById("modal").style.display = "flex";
});

document.getElementById("closeButton").addEventListener("click", function () {
    document.getElementById("modal").style.display = "none";
});

// Função para enviar os dados do formulário via AJAX
document.getElementById("guestForm").addEventListener("submit", function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    fetch("adicionar_convidado.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
            location.reload();
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        alert('Erro inesperado na requisição. Verifique o console para detalhes.');
    });
});

// Função para preencher a tabela com os dados dos convidados
// Função para preencher a tabela com os dados dos convidados
function preencherTabela(convidados) {
    const tabelaBody = document.querySelector('#guests-table tbody');
    tabelaBody.innerHTML = ''; // Limpa a tabela para evitar duplicações.

    convidados.forEach(convidado => {
        const linha = `
            <tr>
                <td>
                    <input 
                        type="text" 
                        value="${convidado.nome}" 
                        disabled 
                        class="nome-input" 
                        data-id="${convidado.id}">
                </td>
                <td>
                    <input 
                        type="text" 
                        value="${convidado.contato}" 
                        disabled 
                        class="contato-input" 
                        data-id="${convidado.id}">
                </td>
                <td>
                    <input 
                        type="checkbox" 
                        ${convidado.convite_enviado ? 'checked' : ''} 
                        disabled 
                        class="convite-checkbox" 
                        data-id="${convidado.id}">
                </td>
                <td>
                    <input 
                        type="checkbox" 
                        ${convidado.presenca_confirmada ? 'checked' : ''} 
                        disabled 
                        class="presenca-checkbox" 
                        data-id="${convidado.id}">
                </td>
                <td>
                    <span class="edit-btn" data-id="${convidado.id}"><i class="bx bx-edit"></i></span>
                    <span class="delet-btn" data-id="${convidado.id}"><i class="bx bx-x"></i></span>
                </td>
            </tr>
        `;
        tabelaBody.insertAdjacentHTML('beforeend', linha);
    });

    // Configura os botões de edição
    configurarBotoesEdicao();
}


// Função para carregar os convidados do banco de dados
function carregarConvidados() {
    const id_evento = new URLSearchParams(window.location.search).get('id_evento');

    fetch(`buscar_convidados.php?id_evento=${id_evento}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                preencherTabela(data);
            }
        });
}

// Função para habilitar os campos de edição e salvar alterações
function configurarBotoesEdicao() {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const tr = this.closest('tr');
            
            // Habilita os campos para edição
            tr.querySelectorAll('input').forEach(input => {
                input.disabled = false;
            });

            // Muda o ícone do botão de editar para salvar
            this.innerHTML = '<i class="bx bx-save"></i>';
            this.classList.add('save-btn');
            this.classList.remove('edit-btn');

            // Alterar para "Salvar"
            this.addEventListener('click', function () {
                salvarAlteracoes(id, tr);
            });
        });
    });
}

// Função para salvar as alterações no banco de dados
function salvarAlteracoes(id, tr) {
    const nome = tr.querySelector('.nome-input').value;
    const contato = tr.querySelector('.contato-input').value;
    const convite_enviado = tr.querySelector('.convite-checkbox').checked ? 1 : 0;
    const presenca_confirmada = tr.querySelector('.presenca-checkbox').checked ? 1 : 0;

    const data = {
        id: id,
        nome: nome,
        contato: contato,
        convite_enviado: convite_enviado,
        presenca_confirmada: presenca_confirmada
    };

    fetch('editar_convidado.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Alterações salvas com sucesso!');
            location.reload(); // Recarrega a página para atualizar a tabela
        } else {
            alert('Erro ao salvar alterações');
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        alert('Erro ao salvar alterações');
    });
}

// Carrega os convidados ao carregar a página
document.addEventListener('DOMContentLoaded', carregarConvidados);

// Delegação de eventos para edição e exclusão
document.querySelector('#guests-table tbody').addEventListener('click', function (event) {
    // Excluir convidado
    if (event.target.closest('.delet-btn')) {
        const id = event.target.closest('.delet-btn').getAttribute('data-id');
        if (confirm("Tem certeza que deseja excluir?")) {
            fetch(`excluir_convidado.php?id=${id}`, { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro ao excluir convidado');
                    }
                });
        }
    }
});
