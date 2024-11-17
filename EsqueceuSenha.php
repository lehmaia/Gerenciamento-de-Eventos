<?php
session_start();
ob_start();
include_once ("database/connSenha.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'lib/vendor/autoload.php';
$mail = new PHPMailer(true);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <!--Logo-->
  <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Esqueceu Senha</title>
  <link rel="stylesheet" href="Entrada.css">
</head>

<body>
  <?php
  $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  
  if (!empty($dados['RecuperarSenha'])) {
    $query_usuario = "SELECT id, nome, email
    FROM Usuario
    WHERE email = :email
    LIMIT 1";
    $result_usuario = $connSenha->prepare($query_usuario);
    $result_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
    $result_usuario->execute();
    
    if (($result_usuario) and ($result_usuario->rowCount() != 0)){
      $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
      $chave_recuperar_senha = password_hash($row_usuario['id'], PASSWORD_DEFAULT);
      
      $query_up_usuario = "UPDATE Usuario
      SET recuperar_senha =:recuperar_senha
      WHERE id =:id
      LIMIT 1";
      $result_up_usuario = $connSenha->prepare($query_up_usuario);
      $result_up_usuario->bindParam(':recuperar_senha', $chave_recuperar_senha, PDO::PARAM_STR);
      $result_up_usuario->bindParam(':id', $row_usuario['id'], PDO::PARAM_INT);
      
      if ($result_up_usuario->execute()) {
        $link = "http://localhost/TCC/atualizarSenha.php?chave=$chave_recuperar_senha";
        
        try {
          $mail->CharSet = 'UTF-8';
          $mail->isSMTP();
          $mail->Host         = 'smtp.gmail.com';
          $mail->SMTPAuth     = true;
          $mail->Username     = ' ';
          $mail->Password     = 'jbfz sfoz sqoc osjj';
          $mail->SMTPSecure   = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port         = 587;
          
          $mail->setFrom('tcczeiros23@gmail.com', 'Atendimento SearchBook');
          $mail->addAddress($row_usuario['email'], $row_usuario['nome']);
          
          $mail->isHTML(true);
          $mail->Subject = 'Recuperar Senha';
          $mail->Body    = 'Prezado(a) '.$row_usuario['nome'].".<br><br> Você solicitou alteração de senha.<br><br>Para continuar o processo de recuperação de senha, clique no link abaixo ou cole o endereço no seu navegador: <br><br><a href='".$link."'>".$link."</a><br><br>Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.<br><br>";
          $mail->AltBody = 'Prezado(a) '.$row_usuario['nome']."\n\nVocê solicitou alteração de senha.\n\nPara continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço no seu navegador: \n\n" . $link . "\n\nSe você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.\n\n";
          
          $mail->send();
          
          $_SESSION['msg'] = "<p style='color: green'> Enviado e-mail com instruções para recuperar a senha. Acesse a sua caixa de e-mail para recuperar a senha!</p>";
          header("Location: LoginAdmin.php");
        }
        catch(Exception $e) {
          echo "Erro: Falha ao enviar e-mail. Mailer Error:{$mail->ErrorInfo}";
        }
      }
      else {
        echo "<p style='color: red'>Erro: Tente novamente!</p>";
      }
    }
    else {
      echo "<p style='color: red>Erro: Usuário não encontrado!</p>";
            }
          }
          
          if (isset($_SESSION['msg_rec'])) {
            echo $_SESSION['msg_rec'];
            unset($_SESSION['msg_rec']);
          }
          
          ?>
          <div class="container">
            <div class="form-image">
              <img src="img/LogoNome.png">
    </div>
    <div class="form">
      <form action="" method="post">
        <div class="form-header">
          <div class="tittle">
            <h1>Esqueceu Senha</h1>
          </div>

          <div class="returnbutton">
            <button onclick="history.go(-1)">Voltar</button>
          </div>
        </div>

        <div class="input-group">
          <div class="input-box">
            <?php
            $usuario = "";
            if (isset($dados['email'])) {
                $usuario = $dados['email'];
            }
            ?>
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
          <input type="submit" value="Enviar link" name="RecuperarSenha">
      </form>
    </div>
  </div>
</body>

</html>