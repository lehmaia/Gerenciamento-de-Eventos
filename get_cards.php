<?php
include 'database/conn.php';

// Consulta para obter todos os cartões com seus respectivos list_id
$query = "SELECT id, text, list_id FROM cards ORDER BY id ASC";
$result = $conn->query($query);

$cards = [];

if ($result->num_rows > 0) {
    // Carregar os cartões no array
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
}

// Retornar os cartões em formato JSON
echo json_encode($cards);
?>
