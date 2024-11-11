<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <!--Aba do Navegador-->
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
            <h1>Esqueceu senha</h1>
          </div>

          <div class="returnbutton">
            <button onclick="history.go(-1)">Retornar</button>
          </div>
        </div>

        <div class="input-group">
          <div class="input-box">
            <lable for="email">E-mail</lable>
            <input id="email" type="email" name="email" placeholder="Digite seu email" required>
          </div>
        </div>

        <div class="subtittle">
          <font size="2px">
            <font color="red">Obs</font>: Será enviado um link para o e-mail inserido acima, em que poderá redefinir sua
            senha.
          </font>
        </div>

        <div class="button">
          <input type="submit" value="Enviar link" name="esqueceu senha">
      </form>
    </div>
  </div>
</body>

</html>