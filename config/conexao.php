<?php
$host = "localhost";
$usuario = "root";
$senha = "pickles2202"; // Coloque a senha do seu banco 
$banco = "consulta_facil"; // Coloque o nome do seu banco criado


$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>