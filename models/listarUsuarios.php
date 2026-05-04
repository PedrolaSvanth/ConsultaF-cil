<?php
include '../models/conexao.php';

$sql = "
    SELECT 
        c.id,
        c.nome_completo,
        c.email,
        c.cpf,
        c.data_nascimento,
        c.cep,
        c.endereco,
        c.complemento,
        c.apelido,
        ev.status_verificacao AS status_email
    FROM clientes c
    LEFT JOIN email_verificacoes ev 
        ON c.id = ev.cliente_id
    ORDER BY c.id ASC
";

$resultado = $conn->query($sql);

$linhas = "";

if ($resultado && $resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        // Se não tiver registro de verificação, mostra "Sem verificação"
        $status = $linha['status_email'] ?? 'Sem verificação';

        $linhas .= "
        <tr>
            <td>{$linha['id']}</td>
            <td>{$linha['nome_completo']}</td>
            <td>{$linha['apelido']}</td>
            <td>{$linha['email']}</td>
            <td>{$linha['cpf']}</td>
            <td>{$linha['data_nascimento']}</td>
            <td>{$linha['cep']}</td>
            <td>{$linha['endereco']}</td>
            <td>{$linha['complemento']}</td>
            <td>{$status}</td>
            <td>
                <form action='../models/editarUsuario.php' method='GET' style='display:inline;'>
                    <input type='hidden' name='id' value='{$linha['id']}'>
                    <button type='submit' class='edit'>Editar</button>
                </form>

                <form action='../models/excluirUsuario.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='{$linha['id']}'>
                    <button type='submit' class='deactivate' onclick='return confirm(\"Deseja realmente excluir este usuário?\");'>
                        Excluir
                    </button>
                </form>
            </td>
        </tr>";
    }
} else {
    // 10 colunas de dados + 1 de ações = 11
    $linhas = "<tr><td colspan='11' style='text-align:center;'>Nenhum cliente encontrado</td></tr>";
}

$html = file_get_contents('../views/lista_cadastrados_v2.html');
$html = str_replace('<!-- AQUI VAI A TABELA -->', $linhas, $html);

echo $html;

$conn->close();
?>
