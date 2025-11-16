<?php
$host = "localhost";
$usuario = "root";
$senha = "Leandra@";
$banco = "consulta_facil";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>