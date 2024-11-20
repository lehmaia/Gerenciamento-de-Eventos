<?php
// Iniciar a sessão
session_start();

// Destruir todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redirecionar o usuário para a página de login ou para a página inicial
header("Location: Login.php");
exit(); // Garante que o script pare após o redirecionamento
?>
