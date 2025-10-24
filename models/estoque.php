<?php
class Estoque {
    private $conn;
    private $table_name = "estoque";

    public $id;
    public $produto_id;
    public $quantidade;
    public $data_atualizacao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar entrada de estoque
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET produto_id=:produto_id, 
                      quantidade=:quantidade";

        $stmt = $this->conn->prepare($query);

        $this->produto_id = htmlspecialchars(strip_tags($this->produto_id));
        $this->quantidade = htmlspecialchars(strip_tags($this->quantidade));

        $stmt->bindParam(":produto_id", $this->produto_id);
        $stmt->bindParam(":quantidade", $this->quantidade);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Ler todos os estoques
    public function read() {
        $query = "SELECT e.id, e.produto_id, p.nome as produto_nome, 
                         e.quantidade, e.data_atualizacao
                  FROM " . $this->table_name . " e
                  LEFT JOIN produtos p ON e.produto_id = p.id
                  ORDER BY e.data_atualizacao DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Ler um estoque específico
    public function readOne() {
        $query = "SELECT e.id, e.produto_id, p.nome as produto_nome, 
                         e.quantidade, e.data_atualizacao
                  FROM " . $this->table_name . " e
                  LEFT JOIN produtos p ON e.produto_id = p.id
                  WHERE e.id = :id
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->produto_id = $row['produto_id'];
            $this->quantidade = $row['quantidade'];
            $this->data_atualizacao = $row['data_atualizacao'];
            return true;
        }
        return false;
    }

    // Atualizar estoque
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET quantidade=:quantidade
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->quantidade = htmlspecialchars(strip_tags($this->quantidade));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":quantidade", $this->quantidade);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Deletar estoque
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Buscar estoque por produto
    public function getByProduto() {
        $query = "SELECT e.id, e.produto_id, p.nome as produto_nome, 
                         e.quantidade, e.data_atualizacao
                  FROM " . $this->table_name . " e
                  LEFT JOIN produtos p ON e.produto_id = p.id
                  WHERE e.produto_id = :produto_id
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":produto_id", $this->produto_id);
        $stmt->execute();

        return $stmt;
    }
}
?>
