<?php
include '../models/conexao.php';
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

    $nome_paciente = trim($_POST['nome_paciente'] ?? '');
    $medicamento   = trim($_POST['medicamento'] ?? '');
    $descricao     = trim($_POST['descricao'] ?? '');
    $dosagem       = trim($_POST['dosagem'] ?? '');
    $frequencia    = trim($_POST['frequencia'] ?? '');

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
$stmt->close();

$template = file_get_contents('../views/editarReceita.html');
$template = str_replace('{ID_RECEITA}', htmlspecialchars($id_receita), $template);
$template = str_replace('{NOME_PACIENTE}', htmlspecialchars($receita['nome_paciente']), $template);
$template = str_replace('{MEDICAMENTO}', htmlspecialchars($receita['medicamento']), $template);
$template = str_replace('{DESCRICAO}', htmlspecialchars($receita['descricao']), $template);
$template = str_replace('{DOSAGEM}', htmlspecialchars($receita['dosagem']), $template);
$template = str_replace('{FREQUENCIA}', htmlspecialchars($receita['frequencia']), $template);

echo $template;

$conn->close();
