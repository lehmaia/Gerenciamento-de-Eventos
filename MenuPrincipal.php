<?php 
if(isset($_GET['id']))
{
    session_start();
    include("database/conn.php");

    $id = $_GET['id'];
    $sql = "SELECT * FROM usuario WHERE id = $id";
    $result = $conn->query($sql);

    // Consulta para eventos em andamento, ordenados por data de criação
    $sql_andamento = "SELECT * FROM eventos WHERE status = 'em_andamento' ORDER BY data_criacao DESC";
    $result_andamento = $conn->query($sql_andamento);
    
    // Consulta para eventos concluídos, ordenados por data de criação
    $sql_concluido = "SELECT * FROM eventos WHERE status = 'concluido' ORDER BY data_criacao DESC";
    $result_concluido = $conn->query($sql_concluido);


}
if(!isset($_GET['id']))
{
    die("Você não pode acessar essa página porque não está logado. <p> <a href=\"Login.php\">Ir para o site</a> </p>");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
    <title>Quartzo Azul</title>
    <link rel="stylesheet" href="MenuPrincipal.css">
</head>

<body>
    <!-- Header Simples -->
    <?php include 'HeaderSimples.php'; ?>
    <header class="pesquisa">
        <input type="text" placeholder="🔍 Pesquisar">
        <a href="CriarEvento.php?id=<?php echo $id;?>" class="btn-criar">Criar</a>
    </header>

    <main>
        <h2 class="titulo">Eventos em andamento</h2>
        <div class="grid-container">
            <!-- Card para criar novo evento -->
            <a href="CriarEvento.php?id=<?php echo $id;?>" class="evento criar-novo">
                <div class="criar-icone">+</div>
                <div class="criar-texto">Criar novo evento</div>
            </a>

            <!-- Exibindo os eventos em andamento -->
            <?php while($row = $result_andamento->fetch_assoc()): 
                $evento_id = $row['id']; 
            ?>
                <a href="MenuEvento.php?id=<?php echo $id;?>&id_evento=<?php echo $evento_id;?>" class="evento andamento">
                    <div class="eventos">
                        <div class="evento-nome"><?= $row['nome']; ?></div>
                    </div>
                    <div class="evento-icon">
                        <?= $row['tipo'] == 'publico' ? '👥' : '🔒'; ?>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>

        <h2 class="titulo">Eventos passados</h2>
        <div class="grid-container">
            <!-- Exibindo os eventos concluídos -->
            <?php while($row = $result_concluido->fetch_assoc()):
                $evento_id = $row['id']; 
            ?>
            <a href="MenuEvento.php?id=<?php echo $id;?>&id_evento=<?php echo $evento_id;?>" class="evento concluido">
                <div class="eventos">
                    <div class="evento-nome"><?= $row['nome']; ?></div>
                </div>
                <div class="evento-icon">
                    <?= $row['tipo'] == 'publico' ? '👥' : '🔒'; ?>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>