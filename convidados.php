<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Convidados</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/fontes.css">
  <link rel="stylesheet" href="css/convidados.css">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
</head>
<body class="body">
    <header>
        <?php include 'Header.php'; ?>
    </header>
    <main class="main kaisei-harunoumi-regular">
        <h3>Lista de Convidados</h3>
        <table id="guests-table">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Contato</th>
                <th>Convite Enviado</th>
                <th>Presença</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <button id="addButton">Adicionar</button>

        <div id="modal" class="modal">
            <div class="modal-content">
              <button id="closeButton" class="close-btn">❌</button>
              <h3>Adicionar Convidado</h3>
              <form id="guestForm">
                <div class="input-box">
                  <label for="name">Nome:</label>
                  <input type="text" id="name" name="name" required>
                </div>
                <div class="input-box">
                  <label for="contact">Contato:</label>
                  <input type="text" id="contact" name="contact" required>
                </div>
                <div class="input-box">
                  <label for="invite">Convite Enviado:</label>
                  <select id="invite" name="invite" required>
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                  </select>
                </div>
                <div class="input-box">
                  <label for="presence">Presença Confirmada:</label>
                  <select id="presence" name="presence" required>
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                  </select>
                </div>
                <input type="hidden" name="id_evento" value="<?php echo $_GET['id_evento']; ?>">
                <div class="adicionar">
                  <button type="submit" class="botao">Adicionar</button>
                </div>
              </form>
            </div>
        </div>
    </main>
    <script src="convidados.js"></script>
</body>
</html>
