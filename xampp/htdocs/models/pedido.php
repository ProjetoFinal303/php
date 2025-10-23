<?php
class Pedido {
    private $conn;
    private $table_name = "pedido";

    public $id;
    public $id_cliente;
    public $descricao;
    public $valor;
    public $data;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT id, id_cliente, descricao, valor, data, status FROM " . $this->table_name . " ORDER BY data DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    function read_by_cliente() {
        $query = "SELECT id, id_cliente, descricao, valor, data, status FROM " . $this->table_name . " WHERE id_cliente = ? ORDER BY data DESC";
        $stmt = $this->conn->prepare($query);
        $this->id_cliente=htmlspecialchars(strip_tags($this->id_cliente));
        $stmt->bindParam(1, $this->id_cliente);
        $stmt->execute();
        return $stmt;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET id_cliente=:id_cliente, descricao=:descricao, valor=:valor, data=:data, status=:status";
        $stmt = $this->conn->prepare($query);
        $this->id_cliente=htmlspecialchars(strip_tags($this->id_cliente));
        $this->descricao=htmlspecialchars(strip_tags($this->descricao));
        $this->valor=htmlspecialchars(strip_tags($this->valor));
        $this->data = date('Y-m-d H:i:s'); // Data atual
        $this->status="Pendente";
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":valor", $this->valor);
        $stmt->bindParam(":data", $this->data);
        $stmt->bindParam(":status", $this->status);
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    function update_status() {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
