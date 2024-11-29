<?php
session_start();
include("database/conn.php");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_evento = $_POST['id_evento'];
    $nome = $_POST['name'];
    $contato = $_POST['contact'];
    $convite_enviado = $_POST['invite'] === 'true' ? 1 : 0;
    $presenca_confirmada = $_POST['presence'] === 'true' ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO convidados (id_evento, nome, contato, convite_enviado, presenca_confirmada) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issii", $id_evento, $nome, $contato, $convite_enviado, $presenca_confirmada);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Convidado adicionado com sucesso!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao adicionar convidado: " . $conn->error]);
    }

    $stmt->close();
}

$conn->close();
?>
