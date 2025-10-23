<?php
include '../config/conexao.php';

if (!isset($_GET['token'])) {
    echo "<script>alert('Token inválido!'); window.location.href='../pages/login_v2.html';</script>";
    exit;
}

$token = $_GET['token'];

$stmt = $conn->prepare("SELECT rs.id, rs.usuario_id, u.email 
                        FROM recuperacao_senha rs
                        JOIN usuarios u ON u.id = rs.usuario_id
                        WHERE rs.token = ? AND rs.usado = 0 AND rs.expira_em > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('❌ Link inválido ou expirado.'); window.location.href='../pages/login_v2.html';</script>";
    exit;
}

$dados = $result->fetch_assoc();
$usuario_id = $dados['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($nova_senha !== $confirma_senha) {
        echo "<script>alert('As senhas não coincidem!'); history.back();</script>";
        exit;
    }

    $senhaHash = password_hash($nova_senha, PASSWORD_ARGON2ID);

    $stmt2 = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $stmt2->bind_param("si", $senhaHash, $usuario_id);
    $stmt2->execute();

    $stmt3 = $conn->prepare("UPDATE recuperacao_senha SET usado = 1 WHERE token = ?");
    $stmt3->bind_param("s", $token);
    $stmt3->execute();

    echo "<script>alert('✅ Senha redefinida com sucesso! Faça login novamente.'); window.location.href='../pages/login_v2.html';</script>";
    exit;
}

include '../pages/redefinir_senha_v1.html';
?>
