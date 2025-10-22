<?php 
include '../config/conexao.php';

//coleta de dados 
if($_SERVER['REQUEST_METHOD']=="POST"){
    //coletando dados do usuario
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $data_nasc = $_POST["data_nasc"];
    $cep = $_POST["cep"];
    $endereco = $_POST["endereco"];
    $numero = $_POST["numero"];
    $complemento = $_POST["complemento"];
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($senha !== $confirma_senha){
        echo "As senhas não coincidem!";
        exit;
    }
}

//evitar SQL Injection
//inserindo dados na tabela "usuarios" 
$stmt = $conn->prepare("INSERT INTO 
usuarios (nome, email, cpf, telefone, data_nasc, cep, endereco, numero, complemento, senha)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

//bind - blindo a query
$stmt->bind_param( "ssssssssss", $nome, $email, $cpf, $telefone, $data_nasc, $cep, $endereco, $numero, $complemento, $senha);

if ($stmt->execute()) {
    echo "Usuario cadastrado com sucesso";
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>