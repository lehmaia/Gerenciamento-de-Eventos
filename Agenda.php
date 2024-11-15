<?php
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
    include_once("database/conn.php");

    $evento_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Pega o ID do evento logado

    // Verifica o ID
    //echo "ID do evento: " . $evento_id;

// Verificar se os dados foram enviados
if(isset($_POST['data'])){
    // Receber os dados do formulário
    $titulo = $_POST['titulo'];
    $data = $_POST['data'];
    $hora_inicio = $_POST['inicio'];
    $hora_fim = $_POST['fim'];

    // Usando prepared statement para evitar SQL Injection
        $stmt = $conn->prepare("INSERT INTO agenda (titulo, dia, hora_inicio, hora_fim, evento_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $titulo, $data, $hora_inicio, $hora_fim, $evento_id);

        if ($stmt->execute()) {
            header("Location: Agenda.php?id=$evento_id");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
    <title>Quartzo Azul</title>
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
        // Verificar se a data foi recebida via POST
        if (isset($_POST['selectedDate'])) {
            $selectedDate = $_POST['selectedDate'];
            
            // Formatando a data para o formato do banco de dados (Y-m-d)
            $selectedDate = DateTime::createFromFormat('d.m.Y', $selectedDate)->format('Y-m-d');
            
            // Consulta para buscar eventos na data selecionada
            $sql = "SELECT titulo, hora_inicio, hora_fim FROM agenda WHERE dia = '$selectedDate'";
            $result = $conn->query($sql);
            
            // Verificar se há eventos e exibi-los
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
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
                echo "<p>Nenhum evento encontrado para essa data.</p>";
            }
        }
    
        ?>
    </div>

    <!-- Scripts -->
    <script src="script.js"></script>
</body>

</html>
