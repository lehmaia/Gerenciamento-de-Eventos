<?php 
    include_once("database/conn.php");

    $eventoId = $_GET['id_evento'];

    function getCards($conn, $listId, $eventoId) {
        $stmt = $conn->prepare("SELECT id, text FROM cards WHERE list_id = ? AND evento_id = ?");
        $stmt->bind_param("ii", $listId, $eventoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cards = $result->fetch_all(MYSQLI_ASSOC);
        return $cards;
    }
    
    $todoCards = getCards($conn, 1, $eventoId);
    $inProgressCards = getCards($conn, 2, $eventoId);
    $doneCards = getCards($conn, 3, $eventoId);   
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
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