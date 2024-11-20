<?php
include 'database/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os parâmetros obrigatórios estão presentes
    if (isset($_POST['listId'], $_POST['evento_id'])) {
        $listId = intval($_POST['listId']);
        $eventoId = intval($_POST['evento_id']);
        
        // Verifica se os valores não estão vazios
        if (!empty($listId) && !empty($eventoId)) {
            // Se `cardId` está presente, é uma atualização; caso contrário, é uma inserção
            if (!empty($_POST['cardId'])) {
                $cardId = intval($_POST['cardId']);
                // Atualização do cartão existente
                $stmt = $conn->prepare("UPDATE cards SET list_id = ? WHERE id = ? AND evento_id = ?");
                $stmt->bind_param("iii", $listId, $cardId, $eventoId);
            } else {
                // Inserção de um novo cartão
                if (isset($_POST['cardText']) && !empty($_POST['cardText'])) {
                    $cardText = $_POST['cardText'];
                    $stmt = $conn->prepare("INSERT INTO cards (text, list_id, evento_id) VALUES (?, ?, ?)");
                    $stmt->bind_param("sii", $cardText, $listId, $eventoId);
                } else {
                    echo json_encode(['error' => 'Faltando cardText para criar novo cartão']);
                    exit;
                }
            }
            
            if ($stmt->execute()) {
                // Retorna o ID do novo cartão ou sucesso na atualização
                echo json_encode([
                    'success' => true,
                    'cardId' => $cardId ?: $stmt->insert_id
                ]);
            } else {
                echo json_encode(['error' => 'Erro ao executar a consulta: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Dados inválidos ou incompletos']);
        }
    } else {
        echo json_encode(['error' => 'Faltando dados obrigatórios']);
    }
} else {
    echo json_encode(['error' => 'Método não permitido']);
}

$conn->close();
?>
