CREATE DATABASE consulta_facil;

USE consulta_facil;

CREATE TABLE IF NOT EXISTS usuarios (
id INT PRIMARY KEY auto_increment,
nome varchar(255) not null,
email varchar(255) not null, 
cpf varchar(14) not null,
telefone varchar(20) not null, 
data_nasc DATE not null, 
cep varchar(10) not null, 
endereco varchar(255) not null,
numero varchar(20) not null, 
complemento varchar(255) not null,
senha VARCHAR(255) NOT NULL
);

CREATE TABLE email_verificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    codigo_verificacao VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    expira_em DATETIME NOT NULL,
    status_verificacao ENUM('pendente', 'confirmado', 'expirado', 'cancelado') DEFAULT 'pendente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE recuperacao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expira_em DATETIME NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);