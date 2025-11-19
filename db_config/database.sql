CREATE DATABASE cadastro_cliente_saude;
USE cadastro_cliente_saude;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(150) NOT NULL,
    cpf VARCHAR(150) NOT NULL,
    data_nascimento DATE NOT NULL,
    cep VARCHAR(10) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    complemento VARCHAR(100),
    email VARCHAR(100),
    apelido VARCHAR(50) NOT NULL
);

CREATE TABLE saude (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    atividade_fisica ENUM('Sim','Não'),
    habitos_nocivos ENUM('Sim','Não'),
    diabetes ENUM('Sim','Não'),
    doenca_cronica ENUM('Sim','Não'),
    quais_doencas VARCHAR(255),
    alergias ENUM('Sim','Não'),
    quais_alergias VARCHAR(255),
    tipo_sanguineo VARCHAR(5),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

CREATE TABLE email_verificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    codigo_verificacao VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    expira_em DATETIME NOT NULL,
    status_verificacao ENUM('pendente', 'confirmado', 'expirado', 'cancelado') DEFAULT 'pendente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE recuperacao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expira_em DATETIME NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE IF NOT EXISTS Medicamentos (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nome_comercial VARCHAR(100) NOT NULL,
    principio_ativo VARCHAR(100) NOT NULL,
    uso_restrito BOOLEAN NOT NULL DEFAULT FALSE, -- TRUE para uso restrito (ex: tarja preta), FALSE caso contrário
    quantidade_estoque INT NOT NULL DEFAULT 0,
    unidade_medida VARCHAR(50), -- Ex: mg, comprimidos, ml, etc.
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cria tabela de estoque para Controle_estoque_farmacia
CREATE TABLE IF NOT EXISTS estoque (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  descricao TEXT DEFAULT NULL,
  quantidade INT NOT NULL DEFAULT 0,
  preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Entradas (
    id_entrada INT AUTO_INCREMENT PRIMARY KEY,
    id_medicamento INT NOT NULL,
    nome_medicamento VARCHAR(100) NOT NULL,
    nome_generico VARCHAR(100),
    dosagem VARCHAR(50), -- Ex: 500mg, 10ml, etc.
    registro_anvisa VARCHAR(50) UNIQUE,
    apresentacao VARCHAR(100), -- Ex: comprimidos, xarope, injeção, etc.
    quantidade INT NOT NULL,
    data_entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lote VARCHAR(50), -- Importante para controle de validade
    fabricante VARCHAR(100),
    FOREIGN KEY (id_medicamento) REFERENCES Medicamentos(id_medicamento)
);

CREATE TABLE IF NOT EXISTS Saidas (
    id_saida INT AUTO_INCREMENT PRIMARY KEY,
    id_medicamento INT NOT NULL,
    quantidade INT NOT NULL,
    data_saida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    paciente VARCHAR(100), -- Nome ou ID do paciente, se aplicável
    medico_crm VARCHAR(20), -- CRM do médico, importante para medicamentos restritos
    FOREIGN KEY (id_medicamento) REFERENCES Medicamentos(id_medicamento)
);

CREATE TABLE alarmes_medicacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nome_medicamento VARCHAR(150) NOT NULL,
    horario TIME NOT NULL,
    frequencia_minutos INT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    ultimo_disparo DATETIME NULL,
    
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_alarmes_usuario
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE CASCADE
);

CREATE TABLE receitas_medicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nome_paciente VARCHAR(150) NOT NULL,
    medicamento VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    dosagem VARCHAR(50),
    frequencia VARCHAR(50) NOT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ativo TINYINT NOT NULL DEFAULT 1,
    
    
    CONSTRAINT fk_receitas_usuario
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE CASCADE
);