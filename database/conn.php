<?php 
    
    //Criando váriaveis e armazenando nelas as informações de acesso ao Banco de Dados
    $host = "localhost"; //IP do Banco de Dados
    $username = "root";  //Username do acesso ao MySql
    $password = "";      //Senha do acesso ao MySql
    $db_name = "quartzo_azul";  //Nome do banco de dados

    //Criando uma váriavel '$conn' que receberá todos as informações obtidas anteriormente, para realizar a conexão através do comando 'msqli_connect'
    //$conn = new PDO("mysql:host=$host;dbname=" . $db_name, $username, $password);

    $conn = new mysqli($host, $username, $password, $db_name);
    
    // Segunda opçaõ de conexão --> $conn = mysqli_connect($host, $username, $password, $db_name);

    //Verificar se a conexão foi bem sucedida através do laço if
    if ($conn->connect_errno) 
    {
        //echo "Erro ao conectar com banco de dados";
    }
    else
    {
        //echo"Conectou";
    }
?>
