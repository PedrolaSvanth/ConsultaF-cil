<?php
include '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id                 = isset($_POST['id_medicamento']) ? (int)$_POST['id_medicamento'] : 0;
    $nome_comercial     = trim($_POST['nome_comercial'] ?? '');
    $principio_ativo    = trim($_POST['principio_ativo'] ?? '');
    $uso_restrito       = isset($_POST['uso_restrito']) ? (int)$_POST['uso_restrito'] : 0;
    $quantidade_estoque = (int)($_POST['quantidade_estoque'] ?? 0);
    $unidade_medida     = trim($_POST['unidade_medida'] ?? '');

    if ($id <= 0 || $nome_comercial === '' || $principio_ativo === '') {
        echo "<script>
                alert('Dados inválidos. Verifique os campos obrigatórios.');
                window.history.back();
              </script>";
        exit;
    }

    $sql = "UPDATE Medicamentos
            SET nome_comercial = ?,
                principio_ativo = ?,
                uso_restrito = ?,
                quantidade_estoque = ?,
                unidade_medida = ?
            WHERE id_medicamento = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssissi",
        $nome_comercial,
        $principio_ativo,
        $uso_restrito,
        $quantidade_estoque,
        $unidade_medida,
        $id
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Medicamento atualizado com sucesso!');
                window.location.href = '../controllers/listarMedicamentos.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ Erro ao atualizar: " . addslashes($conn->error) . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../controllers/listarMedicamentos.php");
    exit;
}
