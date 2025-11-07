CREATE DATABASE IF NOT EXISTS cadastro_clientes;
USE cadastro_clientes;

-- Criação da tabela de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    -- Adicione outros campos conforme necessário, por exemplo:
    telefone VARCHAR(20),
    endereco TEXT,
    bairro, VARCHAR(100),
    cidade, VARCHAR(100),
    uf, CHAR(2),
    cep, VARCHAR(8),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);