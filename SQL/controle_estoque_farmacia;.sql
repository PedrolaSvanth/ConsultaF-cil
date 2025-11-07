CREATE DATABASE IF NOT EXISTS controle_estoque_farmacia;
USE controle_estoque_farmacia;

-- Tabela para cadastrar os medicamentos
CREATE TABLE IF NOT EXISTS Medicamentos (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nome_comercial VARCHAR(100) NOTANA NULL,
    principio_ativo VARCHAR(100) NOT NULL,
    uso_restrito BOOLEAN NOT NULL DEFAULT FALSE, -- TRUE para uso restrito (ex: tarja preta), FALSE caso contrário
    quantidade_estoque INT NOT NULL DEFAULT 0,
    unidade_medida VARCHAR(50), -- Ex: mg, comprimidos, ml, etc.
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
