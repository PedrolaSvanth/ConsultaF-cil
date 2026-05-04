<?php
include '../models/conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<script>
            alert('ID de medicamento inválido.');
            window.location.href = '../models/listarMedicamentos.php';
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
            window.location.href = '../models/listarMedicamentos.php';
          </script>";
    exit;
}

$med = $result->fetch_assoc();
$stmt->close();

$template = file_get_contents('../views/editarMedicamento.html');
$template = str_replace('{ID_MEDICAMENTO}', htmlspecialchars($med['id_medicamento']), $template);
$template = str_replace('{NOME_COMERCIAL}', htmlspecialchars($med['nome_comercial']), $template);
$template = str_replace('{PRINCIPIO_ATIVO}', htmlspecialchars($med['principio_ativo']), $template);
$template = str_replace('{USO_RESTRITO_NAO}', $med['uso_restrito'] ? '' : 'selected', $template);
$template = str_replace('{USO_RESTRITO_SIM}', $med['uso_restrito'] ? 'selected' : '', $template);
$template = str_replace('{QUANTIDADE_ESTOQUE}', htmlspecialchars($med['quantidade_estoque']), $template);
$template = str_replace('{UNIDADE_MEDIDA}', htmlspecialchars($med['unidade_medida']), $template);

echo $template;

$conn->close();
