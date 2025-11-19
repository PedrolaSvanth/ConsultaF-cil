<?php
include '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_comercial     = trim($_POST['nome_comercial'] ?? '');
    $principio_ativo    = trim($_POST['principio_ativo'] ?? '');
    $uso_restrito       = isset($_POST['uso_restrito']) ? (int)$_POST['uso_restrito'] : 0;
    $quantidade_estoque = (int)($_POST['quantidade_estoque'] ?? 0);
    $unidade_medida     = trim($_POST['unidade_medida'] ?? '');

    if ($nome_comercial === '' || $principio_ativo === '') {
        echo "<script>
                alert('Preencha os campos obrigatórios: Nome comercial e Princípio ativo.');
                window.history.back();
              </script>";
        exit;
    }

    $sql = "INSERT INTO Medicamentos
              (nome_comercial, principio_ativo, uso_restrito,
               quantidade_estoque, unidade_medida)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssiss",
        $nome_comercial,
        $principio_ativo,
        $uso_restrito,
        $quantidade_estoque,
        $unidade_medida
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Medicamento cadastrado com sucesso!');
                window.location.href = '../controllers/listarMedicamentos.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ Erro ao cadastrar medicamento: " . addslashes($conn->error) . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../controllers/listarMedicamentos.php');
    exit;
}
