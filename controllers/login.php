<?php
session_start();
include '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, nome, email, senha 
                            FROM usuarios 
                            WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

         if (password_verify($senha, $usuario['senha'])) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            header("Location: ../pages/dashboard.html");
            exit;

        } else {
            echo "<script>
                    alert('❌ CPF ou senha incorretos.');
                    window.location.href='../pages/login_v2.html';
                  </script>";
            exit;
        }
    } else {
        echo "<script>
                alert('❌ CPF não encontrado.');
                window.location.href='../pages/login_v2.html';
              </script>";
        exit;
    }
}
?>
