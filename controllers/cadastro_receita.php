<?php
include '../config/conexao.php';
session_start();

// Verifica se o cliente está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$cliente_id = $_SESSION['cliente_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Pegando dados do formulário
    $nome_paciente = $_POST['nome_paciente'];
    $medicamento = $_POST['medicamento'];
    $descricao = $_POST['descricao'];
    $dosagem = $_POST['dosagem'];
    $frequencia = $_POST['frequencia'];

    // SQL para inserir a receita
    $sql = "INSERT INTO receitas_medicas 
            (cliente_id, nome_paciente, medicamento, descricao, dosagem, frequencia)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro no prepare: " . $conn->error);
    }

    $stmt->bind_param("isssss", $cliente_id, $nome_paciente, $medicamento, $descricao, $dosagem, $frequencia);
    $stmt->execute();

    // Após cadastrar, pode voltar para o menu ou outra página
    header("Location: cadastro_receita.php?sucesso=1");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Receita Médica</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastrar Receita Médica</h2>

        <?php if (isset($_GET['sucesso'])): ?>
            <p style="color: green; font-weight: bold;">Receita cadastrada com sucesso!</p>
        <?php endif; ?>

        <form action="" method="POST">

            <label>Nome do Paciente:</label>
            <input type="text" name="nome_paciente" required>

            <label>Medicamento (opcional):</label>
            <input type="text" name="medicamento">

            <label>Descrição / Observações da Receita:</label>
            <textarea name="descricao" required></textarea>

            <label>Dosagem (opcional):</label>
            <input type="text" name="dosagem">

            <label>Frequência:</label>
            <input type="text" name="frequencia" required>

            <button type="submit">Salvar Receita</button>
        </form>

        <br>
        <button href="../controllers/listar_receitas.php">Listar Receitas</button>
        <br>
        <button href="../controllers/telaprincipal.php">Voltar</button>
    </div>
</body>
</html>
