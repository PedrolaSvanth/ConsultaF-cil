<?php
include '../models/conexao.php';
session_start();

// Verifica se o cliente está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$cliente_id = $_SESSION['cliente_id'];
$mensagem_sucesso = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Pegando dados do formulário
    $nome_paciente = trim($_POST['nome_paciente'] ?? '');
    $medicamento = trim($_POST['medicamento'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $dosagem = trim($_POST['dosagem'] ?? '');
    $frequencia = trim($_POST['frequencia'] ?? '');

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
    $stmt->close();

    $mensagem_sucesso = "<p style=\"color: green; font-weight: bold;\">Receita cadastrada com sucesso!</p>";
}

// Carregar e renderizar template
$template = file_get_contents('../views/cadastroReceita.html');
$template = str_replace('{MENSAGEM_SUCESSO}', $mensagem_sucesso, $template);
echo $template;
$conn->close();
