<?php
include '../config/conexao.php';

$id = $_POST['id'];

$sql = "DELETE FROM usuarios WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Cadastro excluído com sucesso!";
} else {
    echo "Erro ao excluir: " . $conn->error;
}

$conn->close();
?>