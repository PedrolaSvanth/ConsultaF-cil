<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../config/conexao.php';

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo "<script>alert('Por favor, insira um e-mail.'); history.back();</script>";
    exit;
}

$stmt = $conn->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Este e-mail não está vinculado a nenhuma conta.'); history.back();</script>";
    exit;
}

$usuario = $result->fetch_assoc();
$usuario_id = $usuario['id'];

$token = bin2hex(random_bytes(16));
$expira_em = date('Y-m-d H:i:s', strtotime('+15 minutes'));

$stmt2 = $conn->prepare("INSERT INTO recuperacao_senha (usuario_id, token, expira_em, usado) VALUES (?, ?, ?, 0)");
$stmt2->bind_param("iss", $usuario_id, $token, $expira_em);
$stmt2->execute();

$link = "http://localhost/ConsultaF-cil/controllers/verificar_token_senha.php?token={$token}";

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jogadorn571@gmail.com';
    $mail->Password = 'tcaz lyfx vdcu vfyz'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('jogadorn571@gmail.com', 'Equipe Consulta Facil');
    $mail->addAddress($email, $usuario['nome']);

    $mail->isHTML(true);
    $mail->Subject = 'Recuperacao de Senha - Consulta Facil';
    $mail->Body = "
        <h3>Olá, {$usuario['nome']}!</h3>
        <p>Você solicitou a redefinição de senha. Clique no link abaixo para criar uma nova senha:</p>
        <p><a href='{$link}'>Redefinir Senha</a></p>
        <p><small>Este link expira em 15 minutos.</small></p>
    ";

    $mail->send();
    echo "<script>alert('E-mail de recuperação enviado com sucesso! Verifique sua caixa de entrada.'); window.location.href='../pages/login_v2.html';</script>";
} catch (Exception $e) {
    echo "<script>alert('Erro ao enviar o e-mail: {$mail->ErrorInfo}'); history.back();</script>";
}
?>
