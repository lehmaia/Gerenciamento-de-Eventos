<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    die("Você não pode acessar essa página porque não está logado. <p><a href='Login.php'>Ir para o site</a></p>");
}

include_once("database/conn.php");

// Verificar o método da requisição
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Obter os dados do corpo da requisição
    parse_str(file_get_contents("php://input"), $data);
    
    if (isset($data['id'])) {
        $cardId = (int)$data['id']; // Garantir que o ID seja tratado como inteiro
        
        // Preparar e executar a exclusão
        $stmt = $conn->prepare("DELETE FROM cards WHERE id = ?");
        $stmt->bind_param("i", $cardId);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Erro ao excluir o cartão']);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'ID do cartão não fornecido']);
    }
}

$conn->close();
?>
