<?php
class Ingrediente {
    private $conn;
    private $table_name = "Ingrediente";

    public $id;
    public $nome;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT id, nome FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome";
        $stmt = $this->conn->prepare($query);
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $stmt->bindParam(":nome", $this->nome);

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