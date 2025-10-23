<?php 
include '../config/conexao.php';
 
if($_SERVER['REQUEST_METHOD']=="POST"){

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $data_nasc = $_POST["data_nasc"];
    $cep = $_POST["cep"];
    $endereco = $_POST["endereco"];
    $numero = $_POST["numero"];
    $complemento = $_POST["complemento"];
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];
    $origem = $_POST["origem"] ?? 'login'; 

    if ($senha !== $confirma_senha){
        echo "As senhas não coincidem!";
        exit;
    }
    $senhaHash = password_hash($senha, PASSWORD_ARGON2ID);

}

$stmt = $conn->prepare("INSERT INTO 
usuarios (nome, email, cpf, telefone, data_nasc, cep, endereco, numero, complemento, senha)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param( "ssssssssss", $nome, $email, $cpf, $telefone, $data_nasc, $cep, $endereco, $numero, $complemento, $senhaHash);

if ($stmt->execute()) {

    $usuario_id = $stmt->insert_id;

    $codigo_verificacao = random_int(100000, 999999);
    $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $stmt2 = $conn->prepare("INSERT INTO email_verificacoes 
            (usuario_id, codigo_verificacao, expira_em, status_verificacao)
            VALUES (?, ?, ?, 'pendente')");
        $stmt2->bind_param("iss", $usuario_id, $codigo_verificacao, $expira_em);

        if ($stmt2->execute()) {

        include '../controllers/confirmacaoEmail.php';

        if ($origem === 'admin') {
                echo "
                <script>
                    alert('✅ Usuário cadastrado com sucesso! Necessaria confirmação com usuario!');
                    window.location.href = '../controllers/listarUsuarios.php';
                </script>";
        } else {
                echo "
                <script>
                    alert('✅ Cadastro realizado com sucesso! Verifique seu e-mail para confirmar sua conta.');
                    window.location.href = '../pages/login_v2.html';
                </script>";
        }

        echo "Usuário cadastrado com sucesso! Verifique seu e-mail para confirmar a conta.";
    } else {
        echo "Erro ao criar código de verificação: " . $stmt2->error;
    }

    $stmt2->close();
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>