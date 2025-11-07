<?php
include 'config/conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_SESSION['cliente_id'];

    $sql = "INSERT INTO saude (cliente_id, atividade_fisica, habitos_nocivos, diabetes, doenca_cronica, quais_doencas, alergias, quais_alergias)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $cliente_id, $_POST['atividade_fisica'], $_POST['habitos_nocivos'],
        $_POST['diabetes'], $_POST['doenca_cronica'], $_POST['quais_doencas'], $_POST['alergias'], $_POST['quais_alergias']);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Boas-vindas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <!-- Logo centralizada -->
        <div class="logo-container">
           <img src="img/logo.png" alt="logo Consulta Fácil" class="logo">


        </div>

        <h2>Seja bem-vindo ao Consulta Fácil, <?php echo htmlspecialchars($_SESSION['apelido']); ?>!</h2>
        
        <form action="cadastro_cliente.php">
            <button type="submit">Alterar cadastro</button>
        </form>
    </div>
</body>
</html>
