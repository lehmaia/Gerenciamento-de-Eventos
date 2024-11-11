<?php
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
    }
    else
    {
        //echo "Erro ao inserir dados na agenda";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Estilo Google</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Header -->
    <?php include 'Header.php'; ?>
    <header class="titulo">
        <div class="left-icons">
            <!-- Ícone de calendário -->
            <button id="calendar-icon">📅</button>
            <!-- Ícone de adicionar -->
            <button id="add-icon">+</button>
        </div>
        <div class="right-controls">
            <span id="current-month"></span>
            <button id="prev-day">←</button>
            <button id="next-day">→</button>
        </div>
    </header>

    <!-- Calendário do mês (tela sobreposta) -->
    <div id="full-calendar" class="calendar-overlay">
        <div class="calendar-header">
            <button id="prev-month">←</button>
            <span id="calendar-month"></span>
            <button id="next-month">→</button>
        </div>
        <div class="calendar-grid">
            <!-- Dias da semana -->
            <div class="calendar-weekdays">
                <span>Dom</span><span>Seg</span><span>Ter</span><span>Qua</span><span>Qui</span><span>Sex</span><span>Sáb</span>
            </div>
            <!-- Dias do mês -->
            <div id="calendar-days"></div>
        </div>
        <button id="close-calendar">✖</button>
    </div>

    <!-- Tela para adicionar agendamentos -->
     <div id="idmodal" class="modal">
        <div class="modal-content">

            <form id="agendamento" method="POST">
               <h3 style="color: #486591;">Adicionar Agendamento</h3>
               
               <label>Título:</label>
               <input id="titulo" type="text" name="titulo" placeholder="Digite o titulo do agendamento">
   
               <label>Data:</label>
               <input id="data" type="date" name="data">
               
               <label>Horário de Início:</label>
               <input id="inicio" type="time" name="inicio">
               
               <label>Horário de Término:</label>
               <input id="fim" type="time" name="fim">
               
               <input type="submit" value="Salvar" name="salvar" id="button"></input>
               <button id="close-calendar">✖</button>
            </form>
        </div>
     </div>

    <!-- Corpo da página com dias e agendamentos -->
    <div id="days-container"></div>
    <div id="main-container"></div>

    <!-- Scripts -->
    <script src="script.js"></script>
</body>

</html>
