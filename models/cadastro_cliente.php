<?php
include '../models/conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Pegando valores enviados pelo formulário
    $nome = trim($_POST['nome_completo'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $data_nasc = $_POST['data_nascimento'] ?? '';
    $cep = trim($_POST['cep'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $apelido = trim($_POST['apelido'] ?? '');

    // 1. SALVAR DADOS NO BANCO
    $sql = "INSERT INTO clientes (nome_completo, cpf, data_nascimento, cep, endereco, complemento, email, apelido)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro no prepare: " . $conn->error);
    }
    $stmt->bind_param("ssssssss", $nome, $cpf, $data_nasc, $cep, $endereco, $complemento, $email, $apelido);
    $stmt->execute();

    // 2. PEGAR O ID DO CLIENTE QUE ACABOU DE SER CRIADO
    $cliente_id = $stmt->insert_id;

    // 3. SALVAR NA SESSÃO
    $_SESSION['apelido'] = $apelido;
    $_SESSION['cliente_id'] = $cliente_id;

    // 4. REDIRECIONAR PARA A TELA DE CADASTRO DE SAÚDE
    header("Location: cadastro_saude.php");
    exit();
}

// Carregar e renderizar template
$template = file_get_contents('../views/cadastroCliente.html');
echo $template;
$conn->close();
