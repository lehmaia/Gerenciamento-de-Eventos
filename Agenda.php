<?php
session_start();
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
    include_once("database/conn.php");

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $evento_id = isset($_GET['id_evento']) ? (int)$_GET['id_evento'] : 0; // Pega o ID do evento logado

    // Verificar se os dados foram enviados via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['selectedDate']) && !empty($_POST['selectedDate'])) {
            $selectedDate = $_POST['selectedDate']; // A data recebida via POST

            // Tente converter a data para o formato 'Y-m-d' (ano-mês-dia)
            $formattedDate = DateTime::createFromFormat('d.m.Y', $selectedDate);
            if ($formattedDate) {
                // Formatar corretamente para Y-m-d
                $formattedDate = $formattedDate->format('Y-m-d');

                // Usando prepared statement para evitar injeção de SQL
                if ($conn) {
                    $sql = "SELECT titulo, hora_inicio, hora_fim FROM agenda WHERE dia = ? ORDER BY hora_inicio ASC";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        // Vincular o parâmetro (data formatada) ao prepared statement
                        $stmt->bind_param('s', $formattedDate); // 's' indica que o parâmetro é uma string
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Exibir os eventos encontrados
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

                        // Fechar o prepared statement
                        $stmt->close();
                    } else {
                        echo "Erro na preparação da consulta: " . $conn->error;
                    }
                }
            } else {
                echo "<p>Formato de data inválido ou data não fornecida.</p>";
            }

            // Finaliza a execução do script, impedindo o carregamento do restante da página
            exit();
        }

        // Lógica para adicionar evento (caso você tenha o formulário)
        if (isset($_POST['titulo'], $_POST['data'], $_POST['inicio'], $_POST['fim'])) {
            $titulo = $_POST['titulo'];
            $data = $_POST['data'];
            $hora_inicio = $_POST['inicio'];
            $hora_fim = $_POST['fim'];

            // Usando prepared statement para evitar SQL Injection
            if ($conn) {
                $stmt = $conn->prepare("INSERT INTO agenda (titulo, dia, hora_inicio, hora_fim, evento_id) VALUES (?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ssssi", $titulo, $data, $hora_inicio, $hora_fim, $evento_id);
                    if ($stmt->execute()) {
                        // Redirecionar para a mesma página (pode ser ajustado conforme necessário)
                        header("Location: Agenda.php?id=$id&id_evento=$evento_id");
                        exit();
                    } else {
                        echo "Erro ao criar evento: " . $conn->error;
                    }
                    $stmt->close(); // Fechar o statement após execução
                } else {
                    echo "Erro na preparação da consulta: " . $conn->error;
                }
            } else {
                echo "Erro de conexão com o banco de dados.";
            }
        }

    } else {
        // Lógica para carregar a página normalmente
        if (isset($_GET['id_evento']) && !empty($_GET['id_evento'])) {
            // Carregar todos os eventos relacionados ao evento específico
            $evento_id = $_GET['id_evento'];

            // Exibir todos os eventos para o evento_id
            // (implementação pode ser semelhante à lógica de carregar eventos pela data)
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
    </div>

    <!-- Scripts -->
    <script src="script.js"></script>
</body>

</html>
