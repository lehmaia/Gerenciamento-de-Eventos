<?php
session_start();
include_once("database/conn.php");

if (isset($_GET['id_evento'])) {
    $id_evento = $_GET['id_evento'];

    $query = "SELECT * FROM convidados WHERE id_evento = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_evento);
    $stmt->execute();
    $result = $stmt->get_result();

    $convidados = [];
    while ($row = $result->fetch_assoc()) {
        $convidados[] = $row;
    }

    echo json_encode($convidados);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Evento não especificado."]);
}
?>
