<?php
$host = "localhost";
$usuario = "root";
$senha = "Pedro147896!"; // Coloque a senha do seu banco 
$banco = "cadastro_cliente_saude"; // Coloque o nome do seu banco criado

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>