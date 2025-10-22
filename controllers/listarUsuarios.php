<?php
include '../config/conexao.php';

$sql = "SELECT * FROM usuarios ORDER BY id ASC";
$resultado = $conn->query($sql);

$linhas = "";

if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $linhas .= "
        <tr>
            <td>{$linha['id']}</td>
            <td>{$linha['nome']}</td>
            <td>{$linha['email']}</td>
            <td>{$linha['cpf']}</td>
            <td>{$linha['telefone']}</td>
            <td>{$linha['data_nasc']}</td>
            <td>{$linha['cep']}</td>
            <td>{$linha['endereco']}</td>
            <td>{$linha['numero']}</td>
            <td>{$linha['complemento']}</td>
            <td>
                
                <form action='../controllers/editarUsuario.php' method='GET' style='display:inline;'>
                    <input type='hidden' name='id' value='{$linha['id']}'>
                    <button type='submit' class='edit'>Editar</button>
                </form>

                <form action='../controllers/excluirUsuario.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='{$linha['id']}'>
                    <button type='submit' class='deactivate' onclick='return confirm(\"Deseja realmente excluir este usuário?\");'>
                        Excluir
                    </button>
                </form>
            </td>
        </tr>";
    }
} else {
    $linhas = "<tr><td colspan='6' style='text-align:center;'>Nenhum usuário encontrado</td></tr>";
}

$html = file_get_contents('../pages/lista_cadastrados_v2.html');
$html = str_replace('<!-- AQUI VAI A TABELA -->', $linhas, $html);

echo $html;

$conn->close();
?>