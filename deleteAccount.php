<?php
// Iniciar sessão
session_start();

// Conectar ao banco de dados
include("database/conn.php");

// Verificar se o parâmetro 'id' foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar a consulta para deletar o usuário
    $query = "DELETE FROM Usuario WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<script>alert('Conta excluída com sucesso!');</script>";
        // Destruir a sessão e redirecionar para a página de login
        session_destroy();
        header("Location: login.php");
        exit;
    } else {
        echo "<script>alert('Erro ao excluir conta. Tente novamente.');</script>";
    }
} else {
    echo "ID não fornecido.";
}
?>
