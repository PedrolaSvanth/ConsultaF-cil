<?php
include '../models/conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_sanguineo = $_POST['tipo_sanguineo'] ?? '';
    $atividade_fisica = $_POST['atividade_fisica'] ?? 'Não';
    $habitos_nocivos = $_POST['habitos_nocivos'] ?? 'Não';
    $diabetes = $_POST['diabetes'] ?? 'Não';
    $doenca_cronica = $_POST['doenca_cronica'] ?? 'Não';
    $quais_doencas = trim($_POST['quais_doencas'] ?? '');
    $alergias = $_POST['alergias'] ?? 'Não';
    $quais_alergias = trim($_POST['quais_alergias'] ?? '');
    $cliente_id = $_SESSION['cliente_id'] ?? 0;

    // Salvar dados de saúde
    if ($cliente_id > 0) {
        $sql = "INSERT INTO saude_cliente (cliente_id, tipo_sanguineo, atividade_fisica, habitos_nocivos, diabetes, doenca_cronica, quais_doencas, alergias, quais_alergias)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro no prepare: " . $conn->error);
        }
        $stmt->bind_param("issssssss", $cliente_id, $tipo_sanguineo, $atividade_fisica, $habitos_nocivos, $diabetes, $doenca_cronica, $quais_doencas, $alergias, $quais_alergias);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ../controllers/boas_vindas.php");
    exit();
}

// Carregar e renderizar template
$template = file_get_contents('../views/cadastroSaude.html');
echo $template;
$conn->close();
