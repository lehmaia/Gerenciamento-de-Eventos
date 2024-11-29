<?php
// Configuração inicial
header('Content-Type: application/json'); // Define o retorno como JSON
include_once("database/conn.php"); // Inclui a conexão com o banco de dados

// Verifica se o ID foi enviado
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID do convidado não foi fornecido.']);
    exit;
}

$id = intval($_GET['id']); // Sanitiza o ID para evitar SQL Injection

// Executa a exclusão no banco de dados
try {
    // Prepara a query de exclusão
    $query = $conn->prepare("DELETE FROM convidados WHERE id = ?");
    $query->bind_param("i", $id); // Associa o parâmetro como inteiro

    if ($query->execute()) {
        echo json_encode(['success' => true, 'message' => 'Convidado excluído com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir o convidado.']);
    }

    // Fecha a consulta
    $query->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
