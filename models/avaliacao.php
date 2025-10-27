<?php
class Avaliacao {
    private $conn;
    private $table_name = "avaliacoes";

    // Propriedades do objeto
    public $id;
    public $produto_id;
    public $cliente_id;
    public $nota;
    public $comentario;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar avaliação
    function create() {
        // Query para inserir o registro
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    produto_id=:produto_id,
                    cliente_id=:cliente_id,
                    nota=:nota,
                    comentario=:comentario";

        $stmt = $this->conn->prepare($query);

        // Sanitizar dados
        $this->produto_id = htmlspecialchars(strip_tags($this->produto_id));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->nota = htmlspecialchars(strip_tags($this->nota));
        $this->comentario = htmlspecialchars(strip_tags($this->comentario));

        // Vincular dados
        $stmt->bindParam(":produto_id", $this->produto_id);
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":nota", $this->nota);
        $stmt->bindParam(":comentario", $this->comentario);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Ler avaliações por produto
    // (A API read_by_produto.php espera 'nome_cliente' e 'avatar_url')
    function readByProduto() {
        $query = "SELECT
                    a.id,
                    a.nota,
                    a.comentario,
                    a.created_at,
                    c.nome as nome_cliente,
                    c.avatar_url 
                FROM
                    " . $this->table_name . " a
                    LEFT JOIN
                        clientes c ON a.cliente_id = c.id
                WHERE
                    a.produto_id = :produto_id
                ORDER BY
                    a.created_at DESC";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->produto_id = htmlspecialchars(strip_tags($this->produto_id));
        
        // Vincular produto_id
        $stmt->bindParam(":produto_id", $this->produto_id);

        $stmt->execute();
        return $stmt;
    }
}
?>
