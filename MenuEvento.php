<?php
session_start(); // Deve ser chamado no início do arquivo, antes de qualquer HTML ou include

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    // Se não estiver logado, exibe a mensagem de erro e redireciona
    die("Você não pode acessar essa página porque não está logado. <p><a href='Login.php'>Ir para o site</a></p>");
}

include_once("database/conn.php");

$id_evento = isset($_GET['id_evento']) ? (int)$_GET['id_evento'] : 0;

function getCards($conn, $listId, $id_evento) {
    $stmt = $conn->prepare("SELECT id, text FROM cards WHERE list_id = ? AND evento_id = ?");
    $stmt->bind_param("ii", $listId, $id_evento);
    $stmt->execute();
    $result = $stmt->get_result();
    $cards = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close(); // Fechar o statement após o uso
    return $cards;
}

$todoCards = getCards($conn, 1, $id_evento);
$inProgressCards = getCards($conn, 2, $id_evento);
$doneCards = getCards($conn, 3, $id_evento);

$conn->close(); // Fechar a conexão com o banco após o uso
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Quartzo Azul</title>
    <link rel="stylesheet" href="MenuEvento.css">
</head>
<body>
    <!-- Header -->
    <header>
        <?php include 'Header.php'; ?>
    </header>

    <main>
        <h3 class="titulo">Tarefas</h3>
        <div class="board-container">
            <div class="board-column" id="todo">
            <h3 class="board-title">A Fazer</h3>
            <div class="card-list" id="todo-list" ondrop="drop(event)" ondragover="allowDrop(event)">
                <?php foreach ($todoCards as $card): ?>
                    <div class="card" id="card-<?= $card['id']; ?>" draggable="true" ondragstart="drag(event)">
                        <?= htmlspecialchars($card['text']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="add-card-btn" onclick="showAddCardForm('todo')">+ Adicionar Cartão</button>
        </div>
        
        <div class="board-column" id="in-progress">
            <h3 class="board-title">Em Progresso</h3>
            <div class="card-list" id="in-progress-list" ondrop="drop(event)" ondragover="allowDrop(event)">
                <?php foreach ($inProgressCards as $card): ?>
                    <div class="card" id="card-<?= $card['id']; ?>" draggable="true" ondragstart="drag(event)">
                        <?= htmlspecialchars($card['text']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="add-card-btn" onclick="showAddCardForm('in-progress')">+ Adicionar Cartão</button>
        </div>
        
        <div class="board-column" id="done">
            <h3 class="board-title">Concluído</h3>
            <div class="card-list" id="done-list" ondrop="drop(event)" ondragover="allowDrop(event)">
                <?php foreach ($doneCards as $card): ?>
                    <div class="card" id="card-<?= $card['id']; ?>" draggable="true" ondragstart="drag(event)">
                        <?= htmlspecialchars($card['text']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="add-card-btn" onclick="showAddCardForm('done')">+ Adicionar Cartão</button>
        </div>
    
        </div>
    
        <div id="add-card-form" class="add-card-form">
            <input type="text" id="card-text" placeholder="Digite o nome do cartão">
            <button onclick="addCard()">Adicionar</button>
            <button onclick="closeAddCardForm()">Cancelar</button>
        </div>
    </main>

    <script src="MenuEvento.js"></script>
</body>
</html>