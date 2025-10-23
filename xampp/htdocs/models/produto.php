<?php
class Produto {
    private $conn;
    private $table_name = "produto";

    // Propriedades do objeto
    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $image_url;
    public $stripe_price_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ler todos os produtos
    function read() {
        $query = "SELECT id, nome, descricao, preco, image_url, stripe_price_id FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Criar novo produto
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome, descricao=:descricao, preco=:preco, image_url=:image_url, stripe_price_id=:stripe_price_id";
        $stmt = $this->conn->prepare($query);
        // Limpar dados
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->descricao=htmlspecialchars(strip_tags($this->descricao));
        $this->preco=htmlspecialchars(strip_tags($this->preco));
        $this->image_url=htmlspecialchars(strip_tags($this->image_url));
        $this->stripe_price_id=htmlspecialchars(strip_tags($this->stripe_price_id));
        // Associar parâmetros
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":stripe_price_id", $this->stripe_price_id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Atualizar um produto existente
    function update() {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, descricao = :descricao, preco = :preco, image_url = :image_url, stripe_price_id = :stripe_price_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        // Limpar dados
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->descricao=htmlspecialchars(strip_tags($this->descricao));
        $this->preco=htmlspecialchars(strip_tags($this->preco));
        $this->image_url=htmlspecialchars(strip_tags($this->image_url));
        $this->stripe_price_id=htmlspecialchars(strip_tags($this->stripe_price_id));
        $this->id=htmlspecialchars(strip_tags($this->id));
        // Associar parâmetros
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':preco', $this->preco);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':stripe_price_id', $this->stripe_price_id);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Apagar um produto
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        // Limpar dados
        $this->id=htmlspecialchars(strip_tags($this->id));
        // Associar parâmetro
        $stmt->bindParam(1, $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
