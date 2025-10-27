<?php
class Pedido {
    private $conn;
    private $table_name = "pedidos";
    
    public $id;
    public $cliente_id;
    public $produto_id;
    public $quantidade;
    public $preco_total;
    public $status;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    function read_by_cliente() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE cliente_id = :cliente_id ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $this->cliente_id);
        $stmt->execute();
        return $stmt;
    }
    
    function create() {
        $query = "INSERT INTO " . $this->table_name . " (cliente_id, produto_id, quantidade, preco_total, status) VALUES (:cliente_id, :produto_id, :quantidade, :preco_total, :status)";
        $stmt = $this->conn->prepare($query);
        
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->produto_id = htmlspecialchars(strip_tags($this->produto_id));
        $this->quantidade = htmlspecialchars(strip_tags($this->quantidade));
        $this->preco_total = htmlspecialchars(strip_tags($this->preco_total));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        $stmt->bindParam(':cliente_id', $this->cliente_id);
        $stmt->bindParam(':produto_id', $this->produto_id);
        $stmt->bindParam(':quantidade', $this->quantidade);
        $stmt->bindParam(':preco_total', $this->preco_total);
        $stmt->bindParam(':status', $this->status);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    function update() {
        $query = "UPDATE " . $this->table_name . " SET cliente_id = :cliente_id, produto_id = :produto_id, quantidade = :quantidade, preco_total = :preco_total, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->produto_id = htmlspecialchars(strip_tags($this->produto_id));
        $this->quantidade = htmlspecialchars(strip_tags($this->quantidade));
        $this->preco_total = htmlspecialchars(strip_tags($this->preco_total));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':cliente_id', $this->cliente_id);
        $stmt->bindParam(':produto_id', $this->produto_id);
        $stmt->bindParam(':quantidade', $this->quantidade);
        $stmt->bindParam(':preco_total', $this->preco_total);
        $stmt->bindParam(':status', $this->status);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    function update_status() {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':status', $this->status);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
