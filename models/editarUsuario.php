<?php 
include '../models/conexao.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $id = $_POST["id"];
    $nome_completo = $_POST["nome_completo"];
    $apelido = $_POST["apelido"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $data_nascimento = $_POST["data_nascimento"];
    $cep = $_POST["cep"];
    $endereco = $_POST["endereco"];
    $complemento = $_POST["complemento"];

    $sql = "UPDATE clientes 
            SET nome_completo = ?, apelido = ?, email = ?, cpf = ?, data_nascimento = ?, cep = ?, endereco = ?, complemento = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $nome_completo, $apelido, $email, $cpf, $data_nascimento, $cep, $endereco, $complemento, $id);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('✅ Cadastro atualizado com sucesso!');
            setTimeout(() => {
                window.location.href = '../models/listarUsuarios.php';
            }, 300);
        </script>";
    } else {
        echo 'Erro ao atualizar o cadastro: ' . $stmt->error;
    }

    $stmt->close();

} else {

    if (!isset($_GET['id'])) {
        die("ID do usuário não informado!");
    }

    $id = $_GET['id'];
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Usuário não encontrado!");
    }

    $usuario = $result->fetch_assoc();
    $stmt->close();

    $html = file_get_contents('../views/editar_usuario_v1.html');

    $html = str_replace('{ID_USUARIO}', htmlspecialchars($usuario['id']), $html);
    $html = str_replace('{NOME_COMPLETO}', htmlspecialchars($usuario['nome_completo']), $html);
    $html = str_replace('{APELIDO}', htmlspecialchars($usuario['apelido']), $html);
    $html = str_replace('{EMAIL}', htmlspecialchars($usuario['email']), $html);
    $html = str_replace('{CPF}', htmlspecialchars($usuario['cpf']), $html);
    $html = str_replace('{DATA_NASCIMENTO}', htmlspecialchars($usuario['data_nascimento']), $html);
    $html = str_replace('{CEP}', htmlspecialchars($usuario['cep']), $html);
    $html = str_replace('{ENDERECO}', htmlspecialchars($usuario['endereco']), $html);
    $html = str_replace('{COMPLEMENTO}', htmlspecialchars($usuario['complemento']), $html);

    echo $html;
}

$conn->close();
exit;
?>
