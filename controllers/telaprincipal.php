<?php
session_start();
include '../config/conexao.php';

$cliente_id = $_SESSION['cliente_id'] ?? null;
$apelido = $_SESSION['apelido'] ?? 'Usuário';

// Buscar dados do cliente
$cliente = null;
$saude = null;

if ($cliente_id) {
    // Dados pessoais
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $cliente = $resultado->fetch_assoc();

    // Dados de saúde
    $stmt = $conn->prepare("SELECT * FROM saude WHERE cliente_id = ?");
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $saude = $resultado->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Consulta Fácil - Home</title>

  <link rel="stylesheet" href="../assets/css/login.css" />
  <link rel="stylesheet" href="../assets/css/home.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
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

    <div class="home-container">
      <h2>Bem-vindo(a)!</h2>
      <p>Escolha uma das opções abaixo:</p>

      <div class="buttons">
        <button class="home-btn"><i class="fas fa-pills"></i> Consultar Medicamentos</button>
        <button class="home-btn" onclick="window.location.href='../controllers/cadastro_receita.php'"><i class="fas fa-file-medical"></i> Verificar Receitas</button>
        <button class="home-btn"><i class="fas fa-bell"></i> Verificar Alarmes de Remédios</button>
        <button class="home-btn"><i class="fas fa-map-marker-alt"></i> Localizar Farmácias</button>
      </div>
    </div>
</div>

<!-- Modal de informações do usuário -->
<div id="modalUser" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('modalUser').style.display='none'">&times;</span>
        <h2>Dados do Usuário</h2>

        <?php if($cliente): ?>
        <div class="card">
            <h3><i class="fas fa-id-card"></i> Dados Pessoais</h3>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($cliente['nome_completo']); ?></p>
            <p><strong>CPF:</strong> <?php echo htmlspecialchars($cliente['cpf']); ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($cliente['data_nascimento']); ?></p>
            <p><strong>CEP:</strong> <?php echo htmlspecialchars($cliente['cep']); ?></p>
            <p><strong>Endereço:</strong> <?php echo htmlspecialchars($cliente['endereco']); ?></p>
            <p><strong>Complemento:</strong> <?php echo htmlspecialchars($cliente['complemento']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($cliente['email']); ?></p>
            <p><strong>Apelido:</strong> <?php echo htmlspecialchars($cliente['apelido']); ?></p>
        </div>
        <?php endif; ?>

        <?php if($saude): ?>
        <div class="card">
            <h3><i class="fas fa-heartbeat"></i> Dados de Saúde</h3>
            <p><strong>Atividade Física:</strong> <?php echo htmlspecialchars($saude['atividade_fisica']); ?></p>
            <p><strong>Hábitos Nocivos:</strong> <?php echo htmlspecialchars($saude['habitos_nocivos']); ?></p>
            <p><strong>Diabetes:</strong> <?php echo htmlspecialchars($saude['diabetes']); ?></p>
            <p><strong>Doença Crônica:</strong> <?php echo htmlspecialchars($saude['doenca_cronica']); ?></p>
            <p><strong>Quais Doenças:</strong> <?php echo htmlspecialchars($saude['quais_doencas']); ?></p>
            <p><strong>Alergias:</strong> <?php echo htmlspecialchars($saude['alergias']); ?></p>
            <p><strong>Quais Alergias:</strong> <?php echo htmlspecialchars($saude['quais_alergias']); ?></p>
            <p><strong>Tipo Sanguíneo:</strong> <?php echo htmlspecialchars($saude['tipo_sanguineo']); ?></p>
        </div>
        <?php endif; ?>

        <!-- Botão Alterar Cadastro -->
        <div class="modal-footer">
            <a href="cadastro_cliente.php" class="btn-alterar">Alterar Cadastro</a>
        </div>
    </div>
</div>

<script>
    // Fechar modal clicando fora
    window.onclick = function(event) {
        let modal = document.getElementById('modalUser');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
