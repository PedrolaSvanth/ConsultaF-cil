<?php
include '../models/conexao.php';
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$cliente_id = $_SESSION['cliente_id'];

if (!isset($_POST['id'])) {
    header("Location: listarReceitas.php");
    exit();
}

$id_receita = (int) $_POST['id'];

$sql = "DELETE from receitas_medicas WHERE id = ? AND cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_receita, $cliente_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "
        <script>
            alert('✅ Receita excluída com sucesso!');
            // Espera 1.5 segundos e volta à página de listagem
            setTimeout(() => {
                window.location.href = '../models/listarReceitas.php';
            }, 100);
        </script>";
} else {
    echo "
        <script>
            alert('❌ Erro ao excluir: " . addslashes($conn->error) . "');
            window.location.href = '../models/listarReceitas.php';
        </script> ";
}