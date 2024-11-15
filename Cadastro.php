<?php 
include_once ("database/conn.php");

if(isset($_POST['email']))
{
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];

    // Inserir dados no banco de dados
    $sql = "INSERT INTO Usuario (nome, email, senha, telefone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $senha, $telefone);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        header("Location: upload_foto.php?id=$user_id");
        exit;
    } else {
        echo "<script>alert('Falha ao cadastrar usuário');</script>";
    }

    //Inserindo dados na Primeira Tabela
    /*$sql1 = "INSERT INTO Usuario (nome,email,senha,telefone)
    VALUES ('$nome','$email','$senha','$telefone')";

    if($conn->query($sql1)==TRUE)
    {
        //echo "Dados inseridos com sucesso na tabela2";
        echo '<script type="text/javascript">';
        //depois tirar o echo abaixo
        echo 'alert("Usuário cadastrado com sucesso!");';
        // mandar para a tela de menu: echo 'window.location.href =".php";';
        echo '</script>';
    }
    else
    {
        //echo "Erro ao inserir dados na tebala2";
        echo '<script type="text/javascript">';
        echo 'alert("Falha ao cadastrar usuário!");';
        echo 'window.location.href ="";';
        echo '</script>';
    }*/
    
}
else
{
    //echo "Erro ao enviar dados para o banco de dados";
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
        <link rel="stylesheet" href="Usuario.css">
        </head>
    <body>
        <div class="container">
            <div class="form-image">
                <img src="img/LogoNome.png">
            </div>
            <div class="form">
                <form id="usuario" method="POST" onsubmit="return ValidarSenha()">
                    <div class="form-header">
                        <div class="tittle">
                            <h1>Criar Conta</h1>
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
                            <lable for="nome">Nome Completo</lable>
                            <input id="nome" type="text" name="nome" placeholder="Digite seu nome" required>
                        </div>

                        <div class="input-box">
                            <lable for="email">E-mail</lable>
                            <input id="email" type="email" name="email" placeholder="Digite seu email" required>
                        </div>

                        <div class="input-box">
                            <lable for="telefone">Telefone</lable>
                            <input id="telefone" type="tel" name="telefone" placeholder="ex: (11) 99999-9999" required>
                        </div>

                        <div class="input-box">
                            <lable for="senha">Crie uma senha</lable>
                            <input id="senha" type="password" name="senha" placeholder="Crie uma senha" method="POST" oninput="ValidarSenha()" required>
                        </div>
                        <p id="requisito-limite" class="requisito">+ 8 caracteres
                        <p id="requisito-maiuscula" class="requisito">1 letra maiúscula
                        <p id="requisito-minuscula" class="requisito">1 letra minúscula
                        <p id="requisito-numero" class="requisito">1 número
                        <p id="requisito-especial" class="requisito">1 caractere especial
                        <p id="mensagem" class=""></p>
                    </div>

                    <div class="cadastro-button">
                        <input type="submit" value="Cadastrar" name="cadUsuario"></input>
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