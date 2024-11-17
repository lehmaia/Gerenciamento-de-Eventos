<?php 
include_once ("database/conn.php");
if(isset($_POST['email']) || isset($_POST['senha']))
{
    if(strlen($_POST['email']) == 0)
    {
        echo "Preencha o campo E-mail";
    }
    else if(strlen($_POST['senha']) == 0)
    {
        echo "Preencha o campo senha";
    }
    else
    {
        $email = $conn->real_escape_string($_POST['email']);
        $senha = $conn->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuario WHERE  email = '$email' AND senha = '$senha'";
        $sql_query = $conn->query($sql_code) or die("Falha na execução do código SQL: " . $conn->error);

        $quantidade = $sql_query->num_rows;
        
        if($quantidade == 1)
        {
            $usuario = $sql_query->fetch_assoc();

            if(!isset($_SESSION))
            {
                session_start();
            }

            $id = $usuario['id'];
            echo "<script>
                    localStorage.setItem('userId', '$userId');
                </script>";

            header("Location: MenuPrincipal.php?id=$id");
        }
        else
        {
            echo "<script>alert('Falha ao logar! CPF ou senha incorretos')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <!--Aba do Navegador-->
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
                                <!--Adicionar caminho para voltar para tela inicial-->
                                <a href="javascript:history.back()"> Voltar </a>
                            </button>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <div class="input-box">
                            <lable for="email">E-mail</lable>
                            <input id="email" type="email" name="email" placeholder="Digite seu email" required>
                        </div>

                        <div class="input-box">
                            <lable for="senha">Senha</lable>
                            <input id="senha" type="password" name="senha" placeholder="Digite sua senha" required>
                        </div>
                    </div>
                    <a href="EsqueceuSenha.php">Esqueci minha senha</a>
                    <div class="button">
                        <input type="submit" value="Entrar" name="login"></input>
                    </div>
                  </form>
            </div>
        </div>
    </body>
</html>