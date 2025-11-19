<?php
include '../config/conexao.php';

// Busca todos os medicamentos
$sql = "SELECT id_medicamento, nome_comercial, principio_ativo, uso_restrito,
               quantidade_estoque, unidade_medida, data_cadastro
        FROM Medicamentos";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Medicamentos</title>
    <link rel="stylesheet" href="../assets/css/lista_cadastrados.css">
</head>

<body>

    <aside class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="../controllers/listarUsuarios.php">Usuários</a></li>
            <li><a href="#" class="active">Medicamentos</a></li>
        </ul>
    </aside>

    <main class="content">
        <header>
            <h1>Medicamentos <span>- Lista de Medicamentos do sistema</span></h1>
            <nav class="breadcrumb">
                <a href="#">Listagem de Medicamentos</a>
            </nav>
        </header>

        <section class="user-section">
            <a class="add-user" href="../pages/cadastro_medicamentos.html">Cadastrar Medicamento</a>

            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome Comercial</th>
                        <th>Princípio Ativo</th>
                        <th>Uso Restrito</th>
                        <th>Qtd. Estoque</th>
                        <th>Unidade</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id_medicamento']; ?></td>
                                <td><?php echo htmlspecialchars($row['nome_comercial']); ?></td>
                                <td><?php echo htmlspecialchars($row['principio_ativo']); ?></td>
                                <td><?php echo $row['uso_restrito'] ? 'Sim' : 'Não'; ?></td>
                                <td><?php echo $row['quantidade_estoque']; ?></td>
                                <td><?php echo htmlspecialchars($row['unidade_medida']); ?></td>
                                <td><?php echo $row['data_cadastro']; ?></td>
                                <td>
                                    <!-- Botão EDITAR -->
                                    <button type="button" class="edit"
                                        onclick="window.location.href='../pages/editar_medicamento.php?id=<?php echo $row['id_medicamento']; ?>'">
                                        Editar
                                    </button>

                                    <!-- Botão EXCLUIR -->
                                    <form action="../controllers/excluirMedicamento.php" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este medicamento?');">
                                        <input type="hidden" name="id_medicamento"
                                            value="<?php echo $row['id_medicamento']; ?>">
                                        <button type="submit" class="deactivate">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">Nenhum medicamento cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

</body>

</html>