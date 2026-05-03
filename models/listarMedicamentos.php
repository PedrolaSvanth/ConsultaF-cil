<?php
include '../models/conexao.php';

// Busca todos os medicamentos
$sql = "SELECT id_medicamento, nome_comercial, principio_ativo, uso_restrito,
               quantidade_estoque, unidade_medida, data_cadastro
        FROM Medicamentos";
        
$resultado = $conn->query($sql);

$linhas = "";
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $linhas .= "<tr>
                        <td>{$row['id_medicamento']}</td>
                        <td>{$row['nome_comercial']}</td>
                        <td>{$row['principio_ativo']}</td>
                        <td>" . ($row['uso_restrito'] ? 'Sim' : 'Não') . "</td>
                        <td>{$row['quantidade_estoque']}</td>
                        <td>{$row['unidade_medida']}</td>
                        <td>{$row['data_cadastro']}</td>
                        <td>
                            <form action='../controllers/editar_medicamento.php' method='GET' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id_medicamento']}'>
                                <button type='submit' class='edit'>Editar</button>
                            </form>

                            <form action='../models/excluirMedicamento.php' method='POST' style='display:inline;' onsubmit='return confirm(\"Tem certeza que deseja excluir este medicamento?\");'>
                                <input type='hidden' name='id_medicamento' value='{$row['id_medicamento']}'>
                                <button type='submit' class='deactivate'>Excluir</button>
                            </form>
                        </td>
                    </tr>";
    }
} else {
    $linhas = "<tr><td colspan='8'>Nenhum medicamento cadastrado.</td></tr>";
}

$html = file_get_contents('../views/listaMedicamentos.html');
$html = str_replace('<!-- AQUI VAI A TABELA -->', $linhas, $html);

echo $html;

$conn->close();
?>

