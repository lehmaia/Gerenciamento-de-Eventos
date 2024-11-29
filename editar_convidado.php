<?php
include_once("database/conn.php");

// Recebe os dados do cliente (em JSON)
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$nome = $data['nome'];
$contato = $data['contato'];
$convite_enviado = $data['convite_enviado'];
$presenca_confirmada = $data['presenca_confirmada'];

// Atualiza os dados no banco de dados
$sql = "UPDATE convidados SET nome = ?, contato = ?, convite_enviado = ?, presenca_confirmada = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiii", $nome, $contato, $convite_enviado, $presenca_confirmada, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o convidado.']);
}
?>
