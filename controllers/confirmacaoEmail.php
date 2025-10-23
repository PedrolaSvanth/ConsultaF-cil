<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../config/conexao.php'; // conexão com o banco

$sql = "
        SELECT 
            u.*, 
            ev.codigo_verificacao,
            ev.status_verificacao AS status_email
        FROM usuarios u
        JOIN email_verificacoes ev ON u.id = ev.usuario_id
        WHERE ev.status_verificacao = 'pendente'
        ORDER BY u.id ASC
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $idUsuario = $row['id'];
        $emailUsuario = $row['email'];
        $nomeUsuario = $row['nome'];
        $codigo = $row['codigo_verificacao'];

        $mail = new PHPMailer(true);

        try {
    
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jogadorn571@gmail.com';
            $mail->Password = 'tcaz lyfx vdcu vfyz'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('jogadorn571@gmail.com', 'Equipe do Consulta Facil');
            $mail->addAddress($emailUsuario, $nomeUsuario);
            $mail->isHTML(true);
            $mail->Subject = 'Verificacao de conta';

            $link = "http://localhost/ConsultaF-cil/controllers/verificar.php?codigo={$codigo}&email={$emailUsuario}";

            $mail->Body = "
                <h3>Olá, {$nomeUsuario}!</h3>
                <p>Seu cadastro está quase completo. Clique no link abaixo para confirmar seu e-mail:</p>
                <p><a href='{$link}'>Confirmar E-mail</a></p>
                <p><small>Se você não fez este cadastro no Consulta Facil, ignore esta mensagem.</small></p>
            ";

            $mail->send();
            echo "✅ E-mail enviado para: {$emailUsuario}<br>";

        } catch (Exception $e) {
            echo "❌ Erro ao enviar e-mail para {$emailUsuario}: {$mail->ErrorInfo}<br>";
        }
    }
} else {
    echo "Nenhum usuário com verificação pendente.";
}
?>