<?php
include '../config/conexao.php';
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$cliente_id = $_SESSION['cliente_id'];

// Busca receitas ativas do cliente
$sql = "SELECT id, nome_paciente, medicamento, frequencia, data_criacao 
        FROM receitas_medicas 
        WHERE cliente_id = ? AND ativo = 1
        ORDER BY data_criacao DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Receitas Médicas</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    
</head>
<body>
    <div class="container">
        <h2>Minhas Receitas Médicas</h2>

        <?php if (isset($_GET['sucesso'])): ?>
            <p style="color: green; font-weight: bold;">Operação realizada com sucesso!</p>
        <?php endif; ?>

        <a href="../controllers/cadastro_receita.php">Cadastrar nova receita</a> |
        <a href="../controllers/telaprincipal.php">Voltar ao início</a>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Medicamento</th>
                    <th>Frequência</th>
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['nome_paciente']); ?></td>
                        <td><?php echo htmlspecialchars($row['medicamento']); ?></td>
                        <td><?php echo htmlspecialchars($row['frequencia']); ?></td>
                        <td><?php echo $row['data_criacao']; ?></td>
                        <td class="acoes">
                            <a href="editar_receita.php?id=<?php echo $row['id']; ?>">Editar</a>
                            <a href="excluir_receita.php?id=<?php echo $row['id']; ?>"
                               onclick="return confirm('Tem certeza que deseja excluir esta receita?');">
                               Excluir
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Você ainda não possui receitas cadastradas.</p>
        <?php endif; ?>
    </div>
</body>
</html>
