<?php
$host = 'localhost';
$username = 'root';
$password = 'SENHA_DO_SEU_MYSQL'; // Aqui é a senha que você utiliza para entrar no mysql 
$dbname = 'consulta_facil';

//conectar 
$conn = new mysqli($host, $username, $password, $dbname);

//verificar conexao
if($conn->connect_error){
    die("erro ao conectar ".$conn->connect_error);
}

?>