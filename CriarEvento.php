<?php
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
    include_once("database/conn.php");

    $usuario_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Pega o ID do usuário logado

    // Verifica o ID
    //echo "ID do usuário: " . $usuario_id;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $status = $_POST['status'];
        $tipo = $_POST['tipo'];
        $cep = $_POST['cep']; // Recebe o CEP

        // Usando prepared statement para evitar SQL Injection
        $stmt = $conn->prepare("INSERT INTO eventos (nome, status, tipo, usuario_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nome, $status, $tipo, $usuario_id);

        if ($stmt->execute()) {
            header("Location: MenuPrincipal.php?id=$usuario_id");
            exit();
        } else {
            echo "Erro ao criar evento: " . $conn->error;
        }
    }
} else {
    echo "ID do usuário inválido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
    <title>Quartzo Azul</title>
    <link rel="stylesheet" href="Usuario.css">
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
                            <h1>Criar Evento</h1>
                        </div>

                        <div class="returnbutton">
                            <button>
                                <!--Adicionar caminho para voltar para tela inicial-->
                                <a href="MenuPrincipal.php?id=<?php echo $usuario_id;?>"> Voltar </a>
                            </button>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <div class="input-box">
                            <lable for="nome">Nome do Evento</lable>
                            <input id="nome" type="text" name="nome" placeholder="Digite um titulo" required>
                        </div>

                        <div class="input-box">
                            <lable for="cep">CEP</lable>
                            <input type="text" id="cep" name="cep" maxlength="9" pattern="\d{5}-\d{3}" placeholder="00000-000" inputmode="numeric">
                        </div>

                        <div class="input-box">
                            <lable for="local">Local</lable>
                            <input id="local" type="text" name="local" placeholder="Digite o local do evento">
                        </div>

                        <div class="input-box">
                            <lable for="numero">Número</lable>
                            <input id="numero" type="text" name="numero" placeholder="Número">
                        </div>

                        <div class="input-box">
                            <lable for="status">Status</lable>
                            <select name="status" required>
                                <option value="em_andamento">Em andamento</option>
                                <option value="concluido">Concluído</option>
                            </select>
                        </div>

                        <div class="input-box">
                            <lable for="nome">Tipo</lable>
                            <select name="tipo" required>
                                <option value="publico">Público</option>
                                <option value="privado">Privado</option>
                            </select>
                        </div>
                    </div>
                    <div class="cadastro-button">
                        <input type="submit" value="Criar" name="criar_evento"></input>
                    </div>
                  </form>
            </div>
        </div>
        <script src="script.js"></script>
</body>
</html>
