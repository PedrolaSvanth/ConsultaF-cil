<?php
session_start();
include '../models/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_SESSION['cliente_id'];

    $atividade_fisica = $_POST['atividade_fisica'];
    $habitos_nocivos = $_POST['habitos_nocivos'];
    $diabetes = $_POST['diabetes'];
    $doenca_cronica = $_POST['doenca_cronica'];
    $quais_doencas = $_POST['quais_doencas'] ?? null;
    $alergias = $_POST['alergias'];
    $quais_alergias = $_POST['quais_alergias'] ?? null;
    $tipo_sanguineo = $_POST['tipo_sanguineo'];

    $sql = "INSERT INTO saude 
           (atividade_fisica, habitos_nocivos, diabetes, doenca_cronica, quais_doencas, alergias, quais_alergias, tipo_sanguineo, cliente_id)
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro no prepare: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssssi",
        $atividade_fisica,
        $habitos_nocivos,
        $diabetes,
        $doenca_cronica,
        $quais_doencas,
        $alergias,
        $quais_alergias,
        $tipo_sanguineo,
        $cliente_id
    );

    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Boas-vindas</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <img src="../assets/img/logo.png" alt="Logo Consulta Fácil" class="logo">

        <h2>Seja bem-vindo ao Consulta Fácil, 
            <?php echo htmlspecialchars($_SESSION['apelido'] ?? 'Usuário'); ?>!
        </h2>

        <div class="btn-container">
            <form action="../models/cadastro_cliente.php" method="get">
                <button type="submit" class="btn">Alterar cadastro</button>
            </form>
        </div>

        <div class="btn-container">
            <a href="../controllers/telaprincipal.php" class="btn">Ir à tela inicial</a>
        </div>

    </div>
</body>
</html>
</body>
</html>
