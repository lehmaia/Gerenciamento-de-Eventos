<?php 
    include_once("database/conn.php");
    $detalhes = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    $sqlDetalhes = "SELECT * FROM eventos WHERE id = $detalhes";
    $resultDetalhes = $conn->query($sqlDetalhes);

    if($resultDetalhes->num_rows > 0)
    {
        while($detalhesEvento = $resultDetalhes->fetch_array())
        {
            $titulo = $detalhesEvento['nome'];
            $status = $detalhesEvento['status'];
            $tipo = $detalhesEvento['tipo'];
            $data_criacao = $detalhesEvento['data_criacao'];
        }
    }

    //da agenda CONFERIR
// Incluir a conexão com o banco de dados
include_once ('database/conn.php');

// Verificar se os dados foram enviados
if(isset($_POST['data'])){
    // Receber os dados do formulário
    $titulo = $_POST['titulo'];
    $data = $_POST['data'];
    $hora_inicio = $_POST['inicio'];
    $hora_fim = $_POST['fim'];

    // Preparar a query para inserir os dados na tabela 'agenda'
    $sql = "INSERT INTO agenda (titulo, dia, hora_inicio, hora_fim) 
              VALUES ('$titulo', '$data', '$hora_inicio', '$hora_fim')";

    if($conn->query($sql)==TRUE)
    {
        //echo "Dados inseridos com sucesso na agenda";
        header("Location: MenuEvento.php");
        exit();  // Importante para garantir que o script pare por aqui
    }
    else
    {
        //echo "Erro ao inserir dados na agenda";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
    <title>Quartzo Azul</title>
    <link rel="stylesheet" href="MenuEvento.css">
</head>
<body>
    <!-- Header -->
    <?php include 'Header.php'; ?>
    <header class="titulo">
        <div class="left-icons">
            <button id="add-icon">+</button>
        </div>
        <div class="right-controls">
            <span id="current-month"></span>
            <button id="prev-day">←</button>
            <button id="next-day">→</button>
        </div>
    </header>

    <!-- Tela para adicionar agendamentos -->
     <div id="idmodal" class="modal">
        <div class="modal-content">

            <form id="agendamento" method="POST">
               <h3 style="color: #486591;">Adicionar Agendamento</h3>
               
               <label>Título:</label>
               <input id="titulo" type="text" name="titulo" placeholder="Digite o titulo do agendamento" required>
   
               <label>Data:</label>
               <input id="data" type="date" name="data" required>
               
               <label>Horário de Início:</label>
               <input id="inicio" type="time" name="inicio" required>
               
               <label>Horário de Término:</label>
               <input id="fim" type="time" name="fim" required>
               
               <input type="submit" value="Salvar" name="salvar" id="button"></input>
               <button id="close-calendar">✖</button>
            </form>
        </div>
     </div>

    <!-- Corpo da página com dias e agendamentos -->
    <div id="days-container"></div>
    <div id="main-container">
        <?php
                // Preparar a consulta para buscar os agendamentos somente daquele dia
                $sql = "SELECT titulo, hora_inicio, hora_fim FROM agenda";
                $result = $conn->query($sql);
            
                // Verificar se há resultados
                if ($result && $result->num_rows > 0) {
                    // Loop para exibir cada evento
                    while ($row = $result->fetch_assoc()) {
                        // Formatar os horários para exibir apenas hora e minutos
                        $hora_inicio_formatada = date("H:i", strtotime($row["hora_inicio"]));
                        $hora_fim_formatada = date("H:i", strtotime($row["hora_fim"]));

                        echo '<div class="evento-container">';
                        echo '<div class="evento-titulo">' . htmlspecialchars($row["titulo"]) . '</div>';
                        echo '<div class="evento-horarios">';
                        echo '<div class="evento-horario">Início: ' . $hora_inicio_formatada . '</div>';
                        echo '<div class="evento-horario">Término: ' . $hora_fim_formatada . '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Nenhum evento encontrado.</p>";
                }
    
        ?>
    </div>

    <!-- Scripts -->
    <script src="script.js"></script>
</body>

</html>
