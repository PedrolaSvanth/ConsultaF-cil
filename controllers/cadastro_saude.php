<?php
include 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    $_SESSION['apelido'] = $_POST['apelido'];

    // Salvar dados pessoais
    $sql = "INSERT INTO clientes (nome_completo, cpf, data_nascimento, cep, endereco, complemento, email, apelido)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $_POST['nome_completo'],$_POST['cpf'], $_POST['data_nascimento'], $_POST['cep'],
        $_POST['endereco'], $_POST['complemento'], $_POST['email'], $_POST['apelido']);
    $stmt->execute();
    $_SESSION['cliente_id'] = $stmt->insert_id;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Saúde</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function mostrarCampo(id, mostrar) {
            document.getElementById(id).style.display = mostrar ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Saúde</h2>
        <form action="boas_vindas.php" method="POST">
            <label>Pratica atividade física?</label>
            <select name="atividade_fisica">
                <option>Não</option>
                <option>Sim</option>
            </select>

            <label>Possui hábitos nocivos?</label>
            <select name="habitos_nocivos">
                <option>Não</option>
                <option>Sim</option>
            </select>

            <label>Possui diabetes?</label>
            <select name="diabetes">
                <option>Não</option>
                <option>Sim</option>
            </select>

            <label>Possui alguma doença crônica?</label>
            <select name="doenca_cronica" onchange="mostrarCampo('campoDoenca', this.value==='Sim')">
                <option>Não</option>
                <option>Sim</option>
            </select>
            <div id="campoDoenca" class="hidden">
                <label>Quais?</label>
                <input type="text" name="quais_doencas">
            </div>

            <label>Possui alergias?</label>
            <select name="alergias" onchange="mostrarCampo('campoAlergia', this.value==='Sim')">
                <option>Não</option>
                <option>Sim</option>
            </select>
            <div id="campoAlergia" class="hidden">
                <label>Quais?</label>
                <input type="text" name="quais_alergias">
            </div>

            <button type="submit">Finalizar cadastro</button>
        </form>
    </div>
</body>
</html>
