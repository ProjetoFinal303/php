<?php
class Produto {
    private $conn;
    private $table_name = "produtos";

    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $stripe_price_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " (nome, descricao, preco, stripe_price_id) VALUES (:nome, :descricao, :preco, :stripe_price_id)";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->stripe_price_id = htmlspecialchars(strip_tags($this->stripe_price_id));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":stripe_price_id", $this->stripe_price_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET 
                      nome = :nome, 
                      descricao = :descricao, 
                      preco = :preco, 
                      stripe_price_id = :stripe_price_id 
                  WHERE 
                      id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitizar dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->stripe_price_id = htmlspecialchars(strip_tags($this->stripe_price_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular dados
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":stripe_price_id", $this->stripe_price_id);
        $stmt->bindParam(":id", $this->id);

        // *** BLOCO DE EXECUÇÃO ATUALIZADO ***
        try {
            if($stmt->execute()) {
                // Verificar se alguma linha foi realmente afetada
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    // Query executou, mas nenhuma linha mudou (talvez o ID não exista)
                    error_log("MODELO PRODUTO->update(): Query executada mas 0 linhas afetadas. ID: " . $this->id);
                    return false; 
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Captura o erro do SQL (habilitado pela Ação 1)
            error_log("MODELO PRODUTO->update() ERRO: " . $e->getMessage());
            return false;
        }
    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        // *** BLOCO DE EXECUÇÃO ATUALIZADO ***
        try {
            if($stmt->execute()) {
                // Verificar se alguma linha foi realmente afetada
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    // Query executou, mas nenhuma linha mudou (ID não existe)
                    error_log("MODELO PRODUTO->delete(): Query executada mas 0 linhas afetadas. ID: " . $this->id);
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Captura o erro do SQL (habilitado pela Ação 1)
            error_log("MODELO PRODUTO->delete() ERRO: " . $e->getMessage());
            return false;
        }
    }
}
?>
