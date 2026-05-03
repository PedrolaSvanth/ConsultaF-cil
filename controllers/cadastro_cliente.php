<?php
include '../models/conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Pegando valores enviados pelo formulário
    $nome = $_POST['nome_completo'];
    $cpf = $_POST['cpf'];
    $data_nasc = $_POST['data_nascimento'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $complemento = $_POST['complemento'];
    $email = $_POST['email'];
    $apelido = $_POST['apelido'];

    // 1. SALVAR DADOS NO BANCO
    $sql = "INSERT INTO clientes (nome_completo, cpf, data_nascimento, cep, endereco, complemento, email, apelido)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}
    $stmt->bind_param("ssssssss", $nome, $cpf, $data_nasc, $cep, $endereco, $complemento, $email, $apelido);
    $stmt->execute();

    // 2. PEGAR O ID DO CLIENTE QUE ACABOU DE SER CRIADO
    $cliente_id = $stmt->insert_id;

   // 3. SALVAR NA SESSÃO
$_SESSION['apelido'] = $apelido;
$_SESSION['cliente_id'] = $cliente_id;

// 4. REDIRECIONAR PARA A TELA DE CADASTRO DE SAÚDE
header("Location: cadastro_saude.php");
exit();

}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        async function buscarCEP() {
            const cep = document.getElementById("cep").value.replace(/\D/g, '');
            if (cep.length === 8) {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                if (!data.erro) {
                    document.getElementById("endereco").value =
                        `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                } else {
                    alert("CEP não encontrado!");
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Cliente</h2>
        <form action="" method="POST">

            <label>Nome Completo:</label>
            <input type="text" name="nome_completo" required>

            <label>CPF:</label>
            <input type="text" name="cpf" required>

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" required>

            <label>CEP:</label>
            <input type="text" id="cep" name="cep" required onblur="buscarCEP()">

            <label>Endereço:</label>
            <input type="text" id="endereco" name="endereco" readonly required>

            <label>Complemento:</label>
            <input type="text" name="complemento">

            <label>E-mail (opcional):</label>
            <input type="email" name="email">

            <label>Como podemos chamá-lo(a)?</label>
            <input type="text" name="apelido" required>

            <button type="submit">Terminar cadastro</button>
        </form>
    </div>
</body>
</html>
