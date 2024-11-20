<?php
include_once ("database/conn.php");

// Verifica se o formulário foi submetido
if (isset($_POST['email']) && isset($_POST['senha'])) {
    // Verifica se os campos não estão vazios
    if (strlen($_POST['email']) == 0) {
        echo "Preencha o campo E-mail";
    } else if (strlen($_POST['senha']) == 0) {
        echo "Preencha o campo senha";
    } else {
        // Escapa os dados para evitar SQL Injection
        $email = $conn->real_escape_string($_POST['email']);
        $senha = $conn->real_escape_string($_POST['senha']);

        // Consulta no banco de dados para verificar o e-mail e senha
        $sql_code = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $conn->query($sql_code) or die("Falha na execução do código SQL: " . $conn->error);

        // Verifica se encontrou um usuário
        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {
            // Inicia a sessão
            session_start();

            // Obtém os dados do usuário
            $usuario = $sql_query->fetch_assoc();
            $id = $usuario['id'];

            // Armazena o ID do usuário na sessão
            $_SESSION['id'] = $id;

            // Redireciona para a página principal
            header("Location: MenuPrincipal.php?id=$id");
            exit(); // Não se esqueça de sair do script após o redirecionamento
        } else {
            echo "<script>alert('Falha ao logar! E-mail ou senha incorretos.')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quartzo Azul</title>
    <link rel="stylesheet" href="Entrada.css">
</head>
<body>
    <div class="container">
        <div class="form-image">
            <img src="img/LogoNome.png">
        </div>
        <div class="form">
            <form id="usuario" method="POST">
                <div class="form-header">
                    <div class="tittle">
                        <h1>Login</h1>
                    </div>

                    <div class="returnbutton">
                        <button>
                            <a href="javascript:history.back()">Voltar</a>
                        </button>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="email">E-mail</label>
                        <input id="email" type="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="input-box">
                        <label for="senha">Senha</label>
                        <input id="senha" type="password" name="senha" placeholder="Digite sua senha" required>
                    </div>
                </div>

                <a href="EsqueceuSenha.php">Esqueci minha senha</a>
                <div class="button">
                    <input type="submit" value="Entrar" name="login">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
