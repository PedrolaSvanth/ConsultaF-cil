<?php include 'config/conexao.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        // Função para buscar endereço via CEP
        async function buscarCEP() {
            const cep = document.getElementById("cep").value.replace(/\D/g, '');
            if (cep.length === 8) {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                if (!data.erro) {
                    document.getElementById("endereco").value = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                } else {
                    alert("CEP não encontrado!");
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Cliente</h2>
        <form action="cadastro_saude.php" method="POST">
            <label>Nome Completo:</label>
            <input type="text" name="nome_completo" required>

            <label>CPF:</label>
            <input type="text" name="cpf" required>

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" required>

            <label>CEP:</label>
            <input type="text" id="cep" name="cep" required onblur="buscarCEP()">

            <label>Endereço:</label>
            <input type="text" id="endereco" name="endereco" readonly required>

            <label>Complemento:</label>
            <input type="text" name="complemento">

            <label>E-mail (opcional):</label>
            <input type="email" name="email">

            <label>Como podemos chamá-lo(a)?</label>
            <input type="text" name="apelido" required>

            <button type="submit">Terminar cadastro</button>
        </form>
    </div>
</body>
</html>
