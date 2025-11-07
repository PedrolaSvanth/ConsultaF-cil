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