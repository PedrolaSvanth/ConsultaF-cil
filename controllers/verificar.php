<?php
include '../config/conexao.php';

if (isset($_GET['codigo']) && isset($_GET['email'])) {
    $codigo = $_GET['codigo'];
    $email = $_GET['email'];

    $sql = "
        SELECT ev.*, u.id AS usuario_id 
        FROM email_verificacoes ev
        JOIN usuarios u ON ev.usuario_id = u.id
        WHERE u.email = ? AND ev.codigo_verificacao = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $codigo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $dados = $result->fetch_assoc();

        if ($dados['status_verificacao'] === 'pendente' && strtotime($dados['expira_em']) > time()) {

            $update = $conn->prepare("UPDATE email_verificacoes SET status_verificacao = 'confirmado' WHERE id = ?");
            $update->bind_param("i", $dados['id']);
            $update->execute();

            header("Location: ../pages/verificacao_sucesso.html");
            exit;

        } elseif (strtotime($dados['expira_em']) <= time()) {
            echo "<script>alert('O link de verificação expirou. Solicite um novo.');</script>";
        } else {
            echo "<script>alert('Seu e-mail já foi confirmado anteriormente.');</script>";
            exit;
        }
    } else {
        echo "<script>alert('O link de verificação foi invalidado. Solicite um novo.');</script>";
        exit;
    }
} else {
    echo "<script>alert('tivemos um erro ao enviar o link de verificação. Solicite um novo.');</script>";
    $conn->close();
    exit;
}
?>