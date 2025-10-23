<?php 
include '../config/conexao.php';

if($_SERVER['REQUEST_METHOD']=="POST"){
    
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $data_nasc = $_POST["data_nasc"];
    $cep = $_POST["cep"];
    $endereco = $_POST["endereco"];
    $numero = $_POST["numero"];
    $complemento = $_POST["complemento"];

    $sql = "UPDATE usuarios 
            SET nome = ?, email = ?, cpf = ?, telefone = ?, data_nasc = ?, cep = ?, endereco = ?, numero = ?, complemento = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $nome, $email, $cpf, $telefone, $data_nasc, $cep, $endereco, $numero, $complemento, $id);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('✅ Cadastro atualizado com sucesso!');
            setTimeout(() => {
                window.location.href = '../controllers/listarUsuarios.php';
            }, 100);
        </script>";
    } else {
        echo "Erro ao atualizar o cadastro: " . $stmt->error;
    }

    $stmt->close();
} else {
   
    if (!isset($_GET['id'])) {
        die("ID do usuário não informado!");
    }

    $id = $_GET['id'];
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Usuário não encontrado!");
    }

    $usuario = $result->fetch_assoc();
    $stmt->close();

    $html = file_get_contents('../pages/editar_usuario_v1.html');

    $html = str_replace('{ID_USUARIO}', htmlspecialchars($usuario['id']), $html);
    $html = str_replace('{NOME}', htmlspecialchars($usuario['nome']), $html);
    $html = str_replace('{EMAIL}', htmlspecialchars($usuario['email']), $html);
    $html = str_replace('{CPF}', htmlspecialchars($usuario['cpf']), $html);
    $html = str_replace('{TELEFONE}', htmlspecialchars($usuario['telefone']), $html);
    $html = str_replace('{DATA_NASC}', htmlspecialchars($usuario['data_nasc']), $html);
    $html = str_replace('{CEP}', htmlspecialchars($usuario['cep']), $html);
    $html = str_replace('{ENDERECO}', htmlspecialchars($usuario['endereco']), $html);
    $html = str_replace('{NUMERO}', htmlspecialchars($usuario['numero']), $html);
    $html = str_replace('{COMPLEMENTO}', htmlspecialchars($usuario['complemento']), $html);

    echo $html;

}

$conn->close();
exit;

?>