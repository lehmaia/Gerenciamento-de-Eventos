<?php 
    
    //Criando váriaveis e armazenando nelas as informações de acesso ao Banco de Dados
    $host = "localhost"; //IP do Banco de Dados
    $username = "root";  //Username do acesso ao MySql
    $password = "";      //Senha do acesso ao MySql
    $db_name = "quartzo_azul";  //Nome do banco de dados

    //Criando uma váriavel '$conn' que receberá todos as informações obtidas anteriormente, para realizar a conexão através do comando 'msqli_connect'
    //$conn = new PDO("mysql:host=$host;dbname=" . $db_name, $username, $password);

    try{
        $connSenha = new PDO("mysql:host=$host;dbname=" . $db_name, $username, $password); 
        //echo "Conexão com banco de dados realizado com sucesso!";
    }catch(PDOException $err){
        //echo "Erro: Conexão com banco de dados não realizado com sucesso. Erro gerado " . $err->getMessage();
    }
   
?>
