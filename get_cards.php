<?php
include 'database/conn.php';

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifica se o parâmetro id_evento foi passado na URL
if (!isset($_GET['id_evento'])) {
    echo json_encode(['error' => 'Parametro id_evento não fornecido']);
    exit;
}

$evento_id = $_GET['id_evento'];

// Prepara a consulta para evitar injeção de SQL
$stmt = $conn->prepare("SELECT * FROM cards WHERE evento_id = ? ORDER BY id ASC");

// Verifica se houve erro na preparação da consulta
if (!$stmt) {
    echo json_encode(['error' => 'Erro na preparação da consulta: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $evento_id);
$stmt->execute();

// Obtém o resultado
$result = $stmt->get_result();

// Verifica se houve erro na consulta
if (!$result) {
    echo json_encode(['error' => 'Erro na execução da consulta: ' . $conn->error]);
    exit;
}

// Verifica se há cartões no resultado
if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Nenhum cartão encontrado para o evento']);
    exit;
}

$cards = [];

// Carrega os cartões no array
while ($row = $result->fetch_assoc()) {
    $cards[] = $row;
}

// Define o cabeçalho para JSON e retorna os cartões em formato JSON
header('Content-Type: application/json');
http_response_code(200);  // Define o status de sucesso HTTP
echo json_encode($cards);

// Fecha a declaração e a conexão
$stmt->close();
$conn->close();

?>
