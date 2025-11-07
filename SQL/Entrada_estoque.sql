CREATE TABLE IF NOT EXISTS Entradas (
    id_entrada INT AUTO_INCREMENT PRIMARY KEY,
    id_medicamento INT NOT NULL,
    quantidade INT NOT NULL,
    nome_medicamento VARCHAR(100) NOT NULL,
    nome_generico VARCHAR(100),
    data_entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lote VARCHAR(50), -- Importante para controle de validade
    data_validade DATE,
    fornecedor VARCHAR(100),
    FOREIGN KEY (id_medicamento) REFERENCES Medicamentos(id_medicamento)
);