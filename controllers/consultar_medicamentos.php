<?php
session_start();
include '../config/conexao.php';

$apelido = $_SESSION['apelido'] ?? 'Usuário';

// Buscar medicamentos cadastrados (sem data_cadastro)
$sql = "SELECT nome_comercial,
               principio_ativo,
               uso_restrito,
               quantidade_estoque,
               unidade_medida
        FROM Medicamentos";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Consulta Fácil - Medicamentos</title>

    <link rel="stylesheet" href="../assets/css/login.css" />
    <link rel="stylesheet" href="../assets/css/home.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <style>
        /* Deixa o card um pouco mais largo só nessa tela */
        .home-container.med-list {
            width: 100%;
            max-width: 650px;
            box-sizing: border-box;
        }

        /* Wrapper da tabela (card dentro do card) */
        .med-table-wrapper {
            margin-top: 15px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            padding: 10px 15px;
            max-width: 100%;
            overflow-x: auto; /* se estourar, cria scroll dentro do card */
        }

        /* Tabela de medicamentos */
        .med-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            table-layout: fixed; /* faz as colunas se ajustarem ao espaço */
        }

        .med-table th {
            background-color: #00a5c6;
            color: #fff;
            text-align: left;
            padding: 8px 10px;
            border-bottom: 2px solid #0091a6;
        }

        .med-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e0f5f9;
        }

        /* Permite quebra de texto dentro das células pra não estourar */
        .med-table th,
        .med-table td {
            word-wrap: break-word;
            word-break: break-word;
        }

        .med-table tr:nth-child(even) {
            background-color: #f4fbfd;
        }

        .med-table tr:hover {
            background-color: #e9f7fb;
        }

        /* Centraliza colunas numéricas */
        .med-table .center {
            text-align: center;
        }

        .back-btn {
            margin-top: 20px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- Botão do usuário no canto superior direito -->
    <div class="user-btn-container">
        <button class="user-btn" onclick="document.getElementById('modalUser').style.display='block'">
            <i class="fas fa-user"></i> <?php echo htmlspecialchars($apelido); ?>
        </button>
    </div>

    <div class="title"><span>Consulta Fácil</span></div>

    <!-- adicionamos a classe med-list pra aplicar o CSS especial -->
    <div class="home-container med-list">
        <h2>Medicamentos Cadastrados</h2>
        <p>Veja abaixo os medicamentos registrados no sistema:</p>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="med-table-wrapper">
                <table class="med-table">
                    <thead>
                        <tr>
                            <th>Nome Comercial</th>
                            <th>Princípio Ativo</th>
                            <th class="center">Uso Restrito</th>
                            <th class="center">Qtd. Estoque</th>
                            <th class="center">Unidade</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome_comercial']); ?></td>
                            <td><?php echo htmlspecialchars($row['principio_ativo']); ?></td>
                            <td class="center"><?php echo $row['uso_restrito'] ? 'Sim' : 'Não'; ?></td>
                            <td class="center"><?php echo (int)$row['quantidade_estoque']; ?></td>
                            <td class="center"><?php echo htmlspecialchars($row['unidade_medida']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Nenhum medicamento cadastrado no momento.</p>
        <?php endif; ?>

        <!-- Botão para voltar à tela principal -->
        <button class="home-btn back-btn"
                onclick="window.location.href='telaprincipal.php'">
            <i class="fas fa-arrow-left"></i> Voltar para a tela inicial
        </button>
    </div>
</div>

<!-- Modal simples de usuário -->
<div id="modalUser" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('modalUser').style.display='none'">&times;</span>
        <h2>Usuário</h2>
        <p>Logado como: <strong><?php echo htmlspecialchars($apelido); ?></strong></p>
        <div class="modal-footer">
            <a href="telaprincipal.php" class="btn-alterar">Voltar</a>
        </div>
    </div>
</div>

<script>
    // Fechar modal clicando fora
    window.onclick = function (event) {
        let modal = document.getElementById('modalUser');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
