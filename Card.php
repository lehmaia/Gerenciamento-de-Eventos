<?php
include 'database/conn.php';

// Verifique se a solicitação foi feita via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifique se os dados são referentes a um novo cartão ou à atualização de posição
    if (isset($_POST['cardText']) && isset($_POST['listId'])) {
        // Criando um novo cartão
        $cardText = $_POST['cardText'];
        $listId = $_POST['listId'];

        // Valide os dados
        if (empty($cardText) || empty($listId)) {
            echo json_encode(['error' => 'Dados inválidos']);
            exit;
        }

        // Prepare a consulta SQL para adicionar o cartão
        $stmt = $conn->prepare("INSERT INTO cards (text, list_id) VALUES (?, ?)");
        $stmt->bind_param("si", $cardText, $listId);

        // Execute a consulta
        if ($stmt->execute()) {
            // Retorne o ID do cartão recém-criado
            echo json_encode(['cardId' => $conn->insert_id, 'text' => $cardText, 'listId' => $listId]);
        } else {
            echo json_encode(['error' => 'Erro ao adicionar o cartão']);
        }
    } elseif (isset($_POST['cardId']) && isset($_POST['listId'])) {
        // Atualizando a posição do cartão
        $cardId = $_POST['cardId'];
        $listId = $_POST['listId'];

        // Valida os dados
        if (empty($cardId) || empty($listId)) {
            echo json_encode(['error' => 'Dados inválidos']);
            exit;
        }

        // Atualiza o list_id do cartão
        $stmt = $conn->prepare("UPDATE cards SET list_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $listId, $cardId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Erro ao atualizar o cartão']);
        }
    }
}
?>
