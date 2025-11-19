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

// Marca a receita como inativa
$sql = "UPDATE receitas_medicas
        SET ativo = 0
        WHERE id = ? AND cliente_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param("ii", $id_receita, $cliente_id);
$stmt->execute();

header("Location: listar_receitas.php?sucesso=1");
exit();
