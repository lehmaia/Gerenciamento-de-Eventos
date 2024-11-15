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
                                <a href=".php"> Voltar </a>
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
        <script>
            function VerificarRequisitos() {
                const senha = document.getElementById("senha").value;
                
                // Requisitos
                const requisitoLimite = document.getElementById("requisito-limite");
                const requisitoMaiuscula = document.getElementById("requisito-maiuscula");
                const requisitoMinuscula = document.getElementById("requisito-minuscula");
                const requisitoNumero = document.getElementById("requisito-numero");
                const requisitoEspecial = document.getElementById("requisito-especial");
        
                // Verifica cada requisito e aplica a classe "cumprido" se for atendido
                if (senha.length >= 8) {
                    requisitoLimite.classList.add("cumprido");
                } else {
                    requisitoLimite.classList.remove("cumprido");
                }
        
                if (/[A-Z]/.test(senha)) {
                    requisitoMaiuscula.classList.add("cumprido");
                } else {
                    requisitoMaiuscula.classList.remove("cumprido");
                }
        
                if (/[a-z]/.test(senha)) {
                    requisitoMinuscula.classList.add("cumprido");
                } else {
                    requisitoMinuscula.classList.remove("cumprido");
                }
        
                if (/[0-9]/.test(senha)) {
                    requisitoNumero.classList.add("cumprido");
                } else {
                    requisitoNumero.classList.remove("cumprido");
                }
        
                if (/[\W_]/.test(senha)) {
                    requisitoEspecial.classList.add("cumprido");
                } else {
                    requisitoEspecial.classList.remove("cumprido");
                }
            }
            function ValidarSenha() {
                VerificarRequisitos();
                const todosRequisitos = document.querySelectorAll(".requisito");
                const todosCumpridos = Array.from(todosRequisitos).every(requisito => 
                    requisito.classList.contains("cumprido")
                );
    
                if (!todosCumpridos) {
                    document.getElementById("mensagem").textContent = "A senha não atende todos os requisitos.";
                    return false; // Impede o envio do formulário
                }else{
                    document.getElementById("mensagem").textContent = " ";
                    return true; // Permite o envio do formulário
                }
    
            }


            var formData = new FormData();

            $(document).ready(function()
            {
                $("#usuario").submit(function(e)
                {
                    e.preventDefault();

                    var nome = $("#nome").val();
                    var email= $("#email").val();
                    var senha = $("#senha").val();
                    var telefone = $("#telefone").val();

                    formData.append("nome", nome);
                    formData.append("email", email);
                    formData.append("telefone", telefone);
                    formData.append("senha", senha);

                    $.ajax({
                        type:"POST", 
                        url: $(this).attr("action"),
                        data: formData, 
                        contentType: false,
                        processData: false,
                        success: function(response)
                        {
                            swal({
                                title: "Usuário cadastrado com sucesso",
                                icon: "success",
                                button: {confirm: true}, 
                            }).then(value=>{
                                if (value)
                                {
                                    window.location.href = "javascript: history.go(-1)";
                                }
                            });
                        },
                        error: function()
                        {
                            swal({
                                title: "Falha ao cadastrar usuário",
                                icon: "error",
                                button: {confirm: true}, 
                            });
                        }
                    });
                });
            });
        </script>
    </body>
</html>