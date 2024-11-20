<?php
include 'database/conn.php';

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    echo json_encode(['error' => 'Falha na conexão com o banco de dados: ' . $conn->connect_error]);
    exit;
}

// Verifica se o parâmetro id_evento foi passado na URL
if (!isset($_GET['id_evento']) || empty($_GET['id_evento'])) {
    echo json_encode(['error' => 'Parâmetro id_evento não fornecido ou vazio']);
    exit;
}

$evento_id = intval($_GET['id_evento']); // Converte para inteiro para evitar injeção de SQL

// Prepara a consulta para evitar injeção de SQL
$stmt = $conn->prepare("SELECT * FROM cards WHERE evento_id = ? ORDER BY id ASC");
if (!$stmt) {
    echo json_encode(['error' => 'Erro na preparação da consulta: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $evento_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se houve erro na consulta
if ($result === false) {
    echo json_encode(['error' => 'Erro na execução da consulta: ' . $conn->error]);
    exit;
}

$cards = [];
while ($row = $result->fetch_assoc()) {
    $cards[] = $row;
}

// Define o cabeçalho para JSON e retorna os cartões
header('Content-Type: application/json');
http_response_code(200); // Define o status HTTP como 200 (sucesso)
echo json_encode($cards);

// Fecha a declaração e a conexão
$stmt->close();
$conn->close();
?>
