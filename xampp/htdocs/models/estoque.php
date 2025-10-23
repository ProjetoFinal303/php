<?php
class Estoque {
    private $conn;
    private $table_name = "estoque";

    public $id;
    public $id_produto;
    public $quantidade;
    public $nome_produto; // Campo para o join

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT e.id, e.id_produto, e.quantidade, p.nome as nome_produto 
                  FROM " . $this->table_name . " e
                  LEFT JOIN produto p ON e.id_produto = p.id
                  ORDER BY p.nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function update() {
        $query = "INSERT INTO " . $this->table_name . " (id_produto, quantidade) VALUES (:id_produto, :quantidade)
                  ON DUPLICATE KEY UPDATE quantidade = :quantidade";
        $stmt = $this->conn->prepare($query);
        $this->id_produto=htmlspecialchars(strip_tags($this->id_produto));
        $this->quantidade=htmlspecialchars(strip_tags($this->quantidade));
        $stmt->bindParam(":id_produto", $this->id_produto);
        $stmt->bindParam(":quantidade", $this->quantidade);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
