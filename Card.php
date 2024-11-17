<?php
include 'database/conn.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se as variáveis existem e não estão vazias
    if (isset($_POST['cardText']) && isset($_POST['listId']) && isset($_POST['evento_id'])) {
        $cardText = $_POST['cardText'];
        $listId = $_POST['listId'];
        $eventoId = $_POST['evento_id']; // Recebe o evento_id da requisição POST

        // Verifica se os valores são válidos
        if (!empty($cardText) && isset($listId) && isset($eventoId)) {
            // Prepara a consulta SQL para inserir o cartão no banco de dados
            $stmt = $conn->prepare("INSERT INTO cards (text, list_id, evento_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sii", $cardText, $listId, $eventoId);
            
            if ($stmt->execute()) {
                // Retorna os dados do cartão adicionado (incluindo o ID gerado)
                $newCardId = $stmt->insert_id;
                echo json_encode([
                    'cardId' => $newCardId,
                    'text' => $cardText
                ]);
            } else {
                echo json_encode(['error' => 'Erro ao adicionar o cartão']);
            }

            // Fecha a declaração e a conexão
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Dados inválidos']);
        }
    } else {
        echo json_encode(['error' => 'Faltando dados obrigatórios']);
    }
}

$conn->close();

?>
