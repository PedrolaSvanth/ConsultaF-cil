<?php
session_start();
include '../models/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e dá uma limpada básica nos dados
    $cpf     = isset($_POST['cpf']) ? trim($_POST['cpf']) : '';
    $apelido = isset($_POST['apelido']) ? trim($_POST['apelido']) : '';

    if ($cpf === '' || $apelido === '') {
        echo "<script>
                alert('❌ Informe o CPF e o apelido para entrar.');
                window.location.href='../pages/login_v2.html';
              </script>";
        exit;
    }

    // Busca na tabela CLIENTES
    $sql = "SELECT id, nome_completo, apelido, cpf 
            FROM clientes 
            WHERE cpf = ? AND apelido = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $cpf, $apelido);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $cliente = $resultado->fetch_assoc();

        // Seta as variáveis de sessão usadas no sistema
        $_SESSION['cliente_id']      = $cliente['id'];
        $_SESSION['cliente_nome']    = $cliente['nome_completo'];
        $_SESSION['cliente_apelido'] = $cliente['apelido'];

        // Redireciona para a home/sistema
        // Troque para a página inicial real do seu sistema
        header("Location: ../controllers/telaprincipal.php");
        exit;
    } else {
        echo "<script>
                alert('❌ CPF ou apelido incorretos.');
                window.location.href='../pages/login_v2.html';
              </script>";
        exit;
    }
}
?>