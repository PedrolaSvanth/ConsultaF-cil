<?php
include '../models/conexao.php';
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$cliente_id = $_SESSION['cliente_id'];

// Busca receitas ativas do cliente
$sql = "SELECT id, nome_paciente, medicamento, dosagem, descricao, frequencia, data_criacao 
        FROM receitas_medicas 
        WHERE cliente_id = ? AND ativo = 1
        ORDER BY data_criacao DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param("i", $cliente_id);
$stmt->execute();

$resultado = $stmt->get_result();

$linhas = "";
if($row = $resultado->fetch_assoc()) {
    do {
        $linhas .= "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nome_paciente']}</td>
                        <td>{$row['medicamento']}</td>
                        <td>{$row['dosagem']}</td>
                        <td>{$row['descricao']}</td>
                        <td>{$row['frequencia']}</td>
                        <td>" . date("d/m/Y", strtotime($row['data_criacao'])) . "</td>
                        <td>
                            <form action='../models/editarReceita.php' method='GET' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' class='edit'>Editar</button>
                            </form>
                            <form action='../models/excluirReceita.php' method='POST' style='display:inline;' onsubmit='return confirm(\"Tem certeza que deseja excluir esta receita?\");'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' class='deactivate'>Excluir</button>
                            </form>
                        </td>
                    </tr>";
    } while ($row = $resultado->fetch_assoc());
} else {
    $linhas = "<tr><td colspan='8' style='text-align:center;'>Você ainda não possui receitas cadastradas.</td></tr>";
}

$html = file_get_contents('../views/listarReceitas.html');
$html = str_replace('<!-- AQUI VAI A TABELA -->', $linhas, $html);

echo $html;

$conn->close();

?>