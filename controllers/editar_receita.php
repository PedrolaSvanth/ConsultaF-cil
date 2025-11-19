<?php
include '../config/conexao.php';
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$cliente_id = $_SESSION['cliente_id'];

if (!isset($_GET['id'])) {
    header("Location: listar_receitas.php");
    exit();
}

$id_receita = (int) $_GET['id'];

// Se enviou o formulário, atualiza
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome_paciente = $_POST['nome_paciente'];
    $medicamento   = $_POST['medicamento'];
    $descricao     = $_POST['descricao'];
    $dosagem       = $_POST['dosagem'];
    $frequencia    = $_POST['frequencia'];

    $sql = "UPDATE receitas_medicas
            SET nome_paciente = ?, medicamento = ?, descricao = ?, dosagem = ?, frequencia = ?
            WHERE id = ? AND cliente_id = ? AND ativo = 1";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro no prepare: " . $conn->error);
    }

    $stmt->bind_param("ssssiii", $nome_paciente, $medicamento, $descricao, $dosagem, $frequencia, $id_receita, $cliente_id);
    $stmt->execute();

    header("Location: listar_receitas.php?sucesso=1");
    exit();
}

// Se for GET, busca os dados pra mostrar no formulário
$sql = "SELECT nome_paciente, medicamento, descricao, dosagem, frequencia
        FROM receitas_medicas
        WHERE id = ? AND cliente_id = ? AND ativo = 1";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}
$stmt->bind_param("ii", $id_receita, $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Receita não encontrada ou não pertence ao usuário logado.");
}

$receita = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Receita Médica</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Editar Receita Médica</h2>

        <form action="" method="POST">

            <label>Nome do Paciente:</label>
            <input type="text" name="nome_paciente" required
                   value="<?php echo htmlspecialchars($receita['nome_paciente']); ?>">

            <label>Medicamento (opcional):</label>
            <input type="text" name="medicamento"
                   value="<?php echo htmlspecialchars($receita['medicamento']); ?>">

            <label>Descrição / Observações da Receita:</label>
            <textarea name="descricao" required><?php echo htmlspecialchars($receita['descricao']); ?></textarea>

            <label>Dosagem (opcional):</label>
            <input type="text" name="dosagem"
                   value="<?php echo htmlspecialchars($receita['dosagem']); ?>">

            <label>Frequência:</label>
            <input type="text" name="frequencia" required
                   value="<?php echo htmlspecialchars($receita['frequencia']); ?>">

            <button type="submit">Salvar Alterações</button>
        </form>

        <br>
        <a href="listar_receitas.php">Voltar para listagem</a>
    </div>
</body>
</html>
