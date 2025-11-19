<?php
include '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id_medicamento']) ? (int)$_POST['id_medicamento'] : 0;

    if ($id <= 0) {
        echo "<script>
                alert('ID de medicamento inválido.');
                window.location.href = '../controllers/listarMedicamentos.php';
              </script>";
        exit;
    }

    // Opcional: usar transação para garantir tudo ou nada
    $conn->begin_transaction();

    try {
        // Apaga dependências primeiro (se existirem)
        $stmt = $conn->prepare("DELETE FROM Saidas WHERE id_medicamento = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM Entradas WHERE id_medicamento = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM estoque WHERE id_medicamento = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Agora apaga o medicamento
        $stmt = $conn->prepare("DELETE FROM Medicamentos WHERE id_medicamento = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $conn->commit();
            echo "<script>
                    alert('✅ Medicamento excluído com sucesso!');
                    window.location.href = '../controllers/listarMedicamentos.php';
                  </script>";
        } else {
            // Nenhuma linha apagada
            $conn->rollback();
            echo "<script>
                    alert('❌ Medicamento não encontrado.');
                    window.location.href = '../controllers/listarMedicamentos.php';
                  </script>";
        }

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>
                alert('❌ Erro ao excluir: " . addslashes($e->getMessage()) . "');
                window.location.href = '../controllers/listarMedicamentos.php';
              </script>";
    }

    $conn->close();
} else {
    header("Location: ../controllers/listarMedicamentos.php");
    exit;
}
