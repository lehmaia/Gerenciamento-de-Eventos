<?php
// Verificar se o usuário está logado
if (!isset($_SESSION)) {
    die("Você não pode acessar essa página porque não está logado. <p> <a href=\"Login.php\">Ir para o site</a> </p>");
}

// Inclui o arquivo de conexão ao banco de dados
include("database/conn.php");

// Obtém o ID do usuário da sessão da URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Executa a consulta para obter as informações do usuário
$sql = "SELECT * FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se encontrou o usuário
if ($result->num_rows > 0) { 
    $usuario = $result->fetch_assoc();
    $nome = $usuario['nome'];
    $email = $usuario['email'];
    $foto = !empty($usuario['foto']) ? $usuario['foto'] : 'Fotos/default.png';

    //Ainda tem que montar
    // Consulta para eventos em andamento, ordenados por data de criação
    $sql_andamento = "SELECT * FROM eventos WHERE status = 'em_andamento' AND usuario_id = $id  ORDER BY data_criacao DESC";
    $result_andamento = $conn->query($sql_andamento);
    
    // Consulta para eventos concluídos, ordenados por data de criação
    $sql_concluido = "SELECT * FROM eventos WHERE status = 'concluido' AND usuario_id = $id ORDER BY data_criacao DESC";
    $result_concluido = $conn->query($sql_concluido);
    
} else {
    die("Usuário não encontrado.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
    <title>Quartzo Azul</title>
    <script src="js/sweetalert.js" type="module"></script>
    <link rel="stylesheet" href="Header.css">
</head>
<body>
    <header class="header">
        <!-- Logo e nome da empresa -->
        <div class="logo-container">
            <img src="img/IconeLogo.png" alt="Logo" class="logo">
            <span class="company-name">Quartzo Azul</span>
        </div>

        <!-- Menu de navegação -->
        <nav class="navbar">
            <div class="dropdown">
                <button class="dropbtn">Eventos</button>
                <div class="dropdown-content">
                    <a href="#">Opção 1</a>
                    <a href="#">Opção 2</a>
                    <a href="#">Opção 3</a>
                </div>
            </div>

            <!-- Foto de perfil com dropdown -->
            <div class="profile-dropdown">
                <img src="<?php echo $foto; ?>" alt="Foto de Perfil" class="profile-pic">
                <div class="profile-content">
                    <span class="account-label">Conta</span>
                    <div class="profile-info">
                        <img src="<?php echo $foto; ?>" alt="Foto do Usuário" class="profile-pic-small">
                        <div>
                            <span class="user-name"><?php echo $nome;?></span>
                            <span class="user-email"><?php echo $email;?></span>
                        </div>
                    </div>
                    <hr>
                    <a href="Perfil.php?id=<?php echo $evento_id;?>">Perfil</a>
                    <a href="#">Mudar de Conta</a>
                    <a href="#">Configurações</a>
                    <a onclick="Sair()">
                        <button>
                            Sair
                        </button>
                        </a>
                </div>
            </div>
        </nav>
    </header>
</body>
    <script>
        function Sair()
        {
          swal
          ({
            title: "Deseja sair?",
            icon: "warning",
            buttons: ["Cancel", true],
          }).then(value => 
           {
            if (value) 
            {
              window.location.href = "Logout.php";
            } 
           })
          return false;
        }
    </script>
</html>
