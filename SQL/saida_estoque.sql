CREATE TABLE IF NOT EXISTS Saidas (
    id_saida INT AUTO_INCREMENT PRIMARY KEY,
    id_medicamento INT NOT NULL,
    quantidade INT NOT NULL,
    data_saida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    paciente VARCHAR(100), -- Nome ou ID do paciente, se aplicável
    medico_crm VARCHAR(20), -- CRM do médico, importante para medicamentos restritos
    FOREIGN KEY (id_medicamento) REFERENCES Medicamentos(id_medicamento)
);