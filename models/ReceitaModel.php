<?php
include '../models/conexao.php';

class ReceitaModel{

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Método para listar receitas
    public function listarReceitas() {

        $sql = "SELECT id, nome_paciente, medicamento, dosagem, descricao, frequencia, data_criacao 
            FROM receitas_medicas 
            WHERE cliente_id = ? AND ativo = 1
            ORDER BY data_criacao DESC";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Erro no prepare: " . $this->conn->error);
            }

        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
    }

    // Método para criar receita
    public function criarReceita($dados){
        $sql = "INSERT INTO receitas_medicas 
            (cliente_id, nome_paciente, medicamento, descricao, dosagem, frequencia)
            VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Erro no prepare: " . $this->conn->error);
        }

        $stmt->bind_param("isssss", $cliente_id, $nome_paciente, $medicamento, $descricao, $dosagem, $frequencia);
        $stmt->execute();


    }

    // Método para atualizar/editar receita

    // Método para excluir receita 
}

?>