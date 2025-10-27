<?php
class Estoque {
    private $conn;
    private $table_name = "estoque";
    
    public $id;
    public $produto_id;
    public $quantidade;
    public $ultima_atualizacao;
    
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
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Atualizar estoque por ID
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET produto_id = :produto_id,
                    quantidade = :quantidade
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->produto_id = htmlspecialchars(strip_tags($this->produto_id));
        $this->quantidade = htmlspecialchars(strip_tags($this->quantidade));
        
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':produto_id', $this->produto_id);
        $stmt->bindParam(':quantidade', $this->quantidade);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Atualizar estoque por produto_id
    public function updateByProdutoId() {
        $query = "UPDATE " . $this->table_name . "
                SET quantidade = :quantidade
                WHERE produto_id = :produto_id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->produto_id = htmlspecialchars(strip_tags($this->produto_id));
        $this->quantidade = htmlspecialchars(strip_tags($this->quantidade));
        
        $stmt->bindParam(':produto_id', $this->produto_id);
        $stmt->bindParam(':quantidade', $this->quantidade);
        
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
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
