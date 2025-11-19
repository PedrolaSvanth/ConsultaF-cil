<?php
include '../config/conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<script>
            alert('ID de medicamento inválido.');
            window.location.href = '../controllers/listarMedicamentos.php';
          </script>";
    exit;
}

$stmt = $conn->prepare("SELECT id_medicamento, nome_comercial, principio_ativo,
                               uso_restrito, quantidade_estoque, unidade_medida
                        FROM Medicamentos
                        WHERE id_medicamento = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<script>
            alert('Medicamento não encontrado.');
            window.location.href = '../controllers/listarMedicamentos.php';
          </script>";
    exit;
}

$med = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Medicamento</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Editar Medicamento</h2>

        <form action="../controllers/atualizarMedicamento.php" method="POST">
            <input type="hidden" name="id_medicamento" value="<?php echo $med['id_medicamento']; ?>">

            <label>Nome Comercial *</label>
            <input type="text" name="nome_comercial"
                   value="<?php echo htmlspecialchars($med['nome_comercial']); ?>" required>

            <label>Princípio Ativo *</label>
            <input type="text" name="principio_ativo"
                   value="<?php echo htmlspecialchars($med['principio_ativo']); ?>" required>

            <label>Uso Restrito (tarja preta, etc.) *</label>
            <select name="uso_restrito" required>
                <option value="0" <?php echo $med['uso_restrito'] ? '' : 'selected'; ?>>Não</option>
                <option value="1" <?php echo $med['uso_restrito'] ? 'selected' : ''; ?>>Sim</option>
            </select>

            <label>Quantidade em Estoque *</label>
            <input type="number" name="quantidade_estoque" min="0"
                   value="<?php echo (int)$med['quantidade_estoque']; ?>" required>

            <label>Unidade de Medida</label>
            <input type="text" name="unidade_medida"
                   value="<?php echo htmlspecialchars($med['unidade_medida']); ?>" 
                   placeholder="Ex.: comprimidos, mg, ml">

            <button type="submit">Salvar alterações</button>

            <div style="margin-top: 10px; text-align:center;">
                <a href="../controllers/listarMedicamentos.php">Voltar para lista de medicamentos</a>
            </div>
        </form>
    </div>
</body>
</html>
