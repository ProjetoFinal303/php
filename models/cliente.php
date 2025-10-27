<?php
class Cliente {
    private $conn;
    private $table_name = "clientes";

    // Propriedades atualizadas para corresponder ao novo schema e APIs
    public $id;
    public $nome;
    public $email;
    public $contato; // Em vez de telefone
    public $senha;
    public $avatar_url;
    public $role;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        // Query atualizada para NUNCA retornar a senha
        $query = "SELECT id, nome, email, contato, avatar_url, role, created_at 
                FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    function readOne() {
        // Query atualizada para NUNCA retornar a senha
        $query = "SELECT id, nome, email, contato, avatar_url, role, created_at
                FROM " . $this->table_name . " 
                WHERE id = :id LIMIT 1";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nome = $row['nome'];
            $this->email = $row['email'];
            $this->contato = $row['contato'];
            $this->avatar_url = $row['avatar_url'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }
    
    function create() {
        // Query atualizada para incluir os novos campos
        $query = "INSERT INTO " . $this->table_name . " 
                (nome, email, contato, senha) 
                VALUES 
                (:nome, :email, :contato, :senha)";
                
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->contato = htmlspecialchars(strip_tags($this->contato));
        $this->senha = htmlspecialchars(strip_tags($this->senha)); // Senha já deve vir com hash da API
        
        // Vincular dados
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":contato", $this->contato);
        $stmt->bindParam(":senha", $this->senha); // Armazena o hash
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    function update() {
        // Query complexa para atualizar campos dinamicamente
        // Nota: Esta query só atualiza os campos que não são nulos.
        // A API (controller) é responsável por popular $this->senha se ela for alterada.
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    nome = :nome,
                    email = :email,
                    contato = :contato,
                    avatar_url = :avatar_url,
                    role = :role";
        
        // Adicionar senha à query SOMENTE se ela foi fornecida
        if(!empty($this->senha)){
            $query .= ", senha = :senha";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitizar dados (propriedades já devem estar setadas pela API)
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->contato = htmlspecialchars(strip_tags($this->contato));
        $this->avatar_url = htmlspecialchars(strip_tags($this->avatar_url));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular dados
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":contato", $this->contato);
        $stmt->bindParam(":avatar_url", $this->avatar_url);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":id", $this->id);
        
        // Vincular senha SOMENTE se ela foi fornecida
        if(!empty($this->senha)){
            $this->senha = htmlspecialchars(strip_tags($this->senha)); // Hash da API
            $stmt->bindParam(":senha", $this->senha);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
