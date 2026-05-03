<?php
include '../models/conexao.php';
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Minhas Receitas Médicas</title>
    <link rel="stylesheet" href="../assets/css/login.css" />
    <link rel="stylesheet" href="../assets/css/home.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <style>
        .home-container.med-list {
            width: 100%;
            max-width: 760px;
            box-sizing: border-box;
        }
        .med-table-wrapper {
            margin-top: 15px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            padding: 10px 15px;
            max-width: 100%;
            overflow-x: auto;
        }
        .med-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            table-layout: fixed;
        }
        .med-table th,
        .med-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0f5f9;
            word-wrap: break-word;
            word-break: break-word;
            text-align: left;
        }
        .med-table th {
            background-color: #00a5c6;
            color: #fff;
            border-bottom: 2px solid #0091a6;
        }
        .med-table tr:nth-child(even) {
            background-color: #f4fbfd;
        }
        .med-table tr:hover {
            background-color: #e9f7fb;
        }
        .med-table .center {
            text-align: center;
        }
        .back-btn {
            margin-top: 20px;
            width: 100%;
        }
        .link-actions {
            display: inline-flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .success-message {
            color: #2d7a2d;
            font-weight: bold;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="title"><span>Consulta Fácil</span></div>

    <div class="home-container med-list">
        <h2>Minhas Receitas Médicas</h2>
        <?php if (isset($_GET['sucesso'])): ?>
            <p class="success-message">Operação realizada com sucesso!</p>
        <?php endif; ?>

        <div class="link-actions">
            <a href="../controllers/cadastro_receita.php" class="home-btn">Cadastrar nova receita</a>
            <a href="../controllers/telaprincipal.php" class="home-btn">Voltar ao início</a>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="med-table-wrapper">
                <table class="med-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Medicamento</th>
                            <th>Frequência</th>
                            <th>Data de Criação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                   onclick="return confirm('Tem certeza que deseja excluir esta receita?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Você ainda não possui receitas cadastradas.</p>
        <?php endif; ?>

        <button class="home-btn back-btn" onclick="window.location.href='telaprincipal.php'">
            <i class="fas fa-arrow-left"></i> Voltar para a tela inicial
        </button>
    </div>
</div>

</body>
</html>
