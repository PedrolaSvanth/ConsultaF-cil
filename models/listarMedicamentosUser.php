<?php
session_start();
include '../models/conexao.php';

$apelido = $_SESSION['apelido'] ?? 'Usuário';

// Buscar medicamentos cadastrados (sem data_cadastro)
$sql = "SELECT nome_comercial,
               principio_ativo,
               uso_restrito,
               quantidade_estoque,
               unidade_medida
        FROM Medicamentos";
$result = $conn->query($sql);

// Construir a tabela
if ($result && $result->num_rows > 0) {
    $linhas = "";
    while ($row = $result->fetch_assoc()) {
        $linhas .= "<tr>
                        <td>" . htmlspecialchars($row['nome_comercial']) . "</td>
                        <td>" . htmlspecialchars($row['principio_ativo']) . "</td>
                        <td class='center'>" . ($row['uso_restrito'] ? 'Sim' : 'Não') . "</td>
                        <td class='center'>" . (int)$row['quantidade_estoque'] . "</td>
                        <td class='center'>" . htmlspecialchars($row['unidade_medida']) . "</td>
                    </tr>";
    }
    $tabela = "<div class='med-table-wrapper'>
                <table class='med-table'>
                    <thead>
                        <tr>
                            <th>Nome Comercial</th>
                            <th>Princípio Ativo</th>
                            <th class='center'>Uso Restrito</th>
                            <th class='center'>Qtd. Estoque</th>
                            <th class='center'>Unidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$linhas}
                    </tbody>
                </table>
            </div>";
} else {
    $tabela = "<p>Nenhum medicamento cadastrado no momento.</p>";
}

$conn->close();

// Carregar template e substituir placeholders
$template = file_get_contents('../views/consultarMedicamentos.html');
$template = str_replace('{APELIDO}', htmlspecialchars($apelido), $template);
$template = str_replace('{TABELA_MEDICAMENTOS}', $tabela, $template);

echo $template;

