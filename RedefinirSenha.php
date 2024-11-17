<?php
include("database/conn.php");

$id = $_GET['id'] ?? 0;

if (isset($_POST['senha'])) {
    // Verificar a senha atual
    $senhaAtual = $conn->real_escape_string($_POST['senha']);
    
    // Buscar a senha no banco de dados
    $sql_code = "SELECT * FROM Usuario WHERE id = '$id' AND senha = '$senhaAtual'";
    $sql_query = $conn->query($sql_code) or die("Falha na execução do código SQL: " . $conn->error);
    $quantidade = $sql_query->num_rows;

    if ($quantidade == 1) {
        // Senha correta, mostrar campos para nova senha
        $mostrarNovoCampos = true;
    } else {
        echo "<script>alert('Senha incorreta!')</script>";
        $mostrarNovoCampos = false;
    }
}

if (isset($_POST['nova_senha'])) {
    // Alterar a senha
    $novaSenha = $conn->real_escape_string($_POST['nova_senha']);
    $confirmarSenha = $conn->real_escape_string($_POST['confirmar_senha']);
    
    if ($novaSenha === $confirmarSenha) {
        // Atualizar a senha no banco de dados
        $stmt = $conn->prepare("UPDATE Usuario SET senha = ? WHERE id = ?");
        $stmt->bind_param("si", $novaSenha, $id);
        $stmt->execute();

        echo "<script>alert('Senha alterada com sucesso!');</script>";
        header("Location: Perfil.php?id=$id");
        exit();
    } else {
        echo "<script>alert('As senhas não coincidem!');</script>";
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id");
        exit();
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id");
    exit();
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
            <form action="" method="post">
                <div class="form-header">
                    <div class="tittle">
                        <h1>Redefinir Senha</h1>
                    </div>
                    <div class="returnbutton">
                        <button><a href="javascript:history.back()">Voltar</a></button>
                    </div>
                </div>

                <?php if (!isset($mostrarNovoCampos) || !$mostrarNovoCampos) { ?>
                <!-- Formulário de Senha Atual -->
                <div class="input-group">
                    <div class="input-box">
                        <label for="senha">Digite sua senha atual:</label>
                        <input id="senha" type="password" name="senha" placeholder="Digite sua senha" required>
                    </div>
                </div>
                <a href="EsqueceuSenha.php">Esqueci minha senha</a>
                <div class="button">
                    <input type="submit" value="Próximo" name="Next">
                </div>
                <?php } else { ?>
                <!-- Formulário de Nova Senha -->
                <div class="input-group">
                    <div class="input-box">
                        <label for="nova_senha">Nova Senha:</label>
                        <input id="nova_senha" type="password" name="nova_senha" placeholder="Digite a nova senha" oninput="ValidarSenha()" required>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="confirmar_senha">Confirmar Nova Senha:</label>
                        <input id="confirmar_senha" type="password" name="confirmar_senha" placeholder="Confirme a nova senha" required>
                    </div>
                </div>

                <p id="requisito-limite" class="requisito">+ 8 caracteres
                <p id="requisito-maiuscula" class="requisito">1 letra maiúscula
                <p id="requisito-minuscula" class="requisito">1 letra minúscula
                <p id="requisito-numero" class="requisito">1 número
                <p id="requisito-especial" class="requisito">1 caractere especial
                <p id="mensagem" class=""></p>

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
                </script>

                <div class="button">
                    <input type="submit" value="Alterar Senha">
                </div>
                <?php } ?>
            </form>
        </div>
    </div>
</body>
</html>
