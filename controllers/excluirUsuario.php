<?php
include '../config/conexao.php';

$id = $_POST['id'];

$sql1 = "DELETE FROM email_verificacoes WHERE usuario_id = $id";
$conn->query($sql1);

$sql2 = "DELETE FROM usuarios WHERE id = $id";

if ($conn->query($sql2) === TRUE) {
    echo "
        <script>
            alert('✅ Cadastro excluído com sucesso!');
            // Espera 1.5 segundos e volta à página de listagem
            setTimeout(() => {
                window.location.href = '../controllers/listarUsuarios.php';
            }, 100);
        </script>";
} else {
    echo "
        <script>
            alert('❌ Erro ao excluir: " . addslashes($conn->error) . "');
            window.location.href = '../controllers/listarUsuarios.php';
        </script> ";
}

$conn->close();
?>