<?php
class Cliente {
    private $conn;
    private $table_name = "Cliente";

    public $id;
    public $nome;
    public $email;
    public $contato;
    public $senha;
    public $avatar_url;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT id, nome, email, contato, avatar_url, role FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    function readOne($by = 'id') {
        $query = "SELECT id, nome, email, contato, senha, avatar_url, role FROM " . $this->table_name;
        
        if ($by == 'email') {
            $query .= " WHERE email = ? LIMIT 0,1";
            $param = htmlspecialchars(strip_tags($this->email));
        } else {
            $query .= " WHERE id = ? LIMIT 0,1";
            $param = htmlspecialchars(strip_tags($this->id));
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $param);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->email = $row['email'];
            $this->contato = $row['contato'];
            $this->senha = $row['senha'];
            $this->avatar_url = $row['avatar_url'];
            $this->role = $row['role'];
            return true;
        }
        return false;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome, email=:email, contato=:contato, senha=:senha, role='user'";
        $stmt = $this->conn->prepare($query);

        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->contato=htmlspecialchars(strip_tags($this->contato));
        $this->senha=password_hash(htmlspecialchars(strip_tags($this->senha)), PASSWORD_DEFAULT); // Criptografando a senha

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":contato", $this->contato);
        $stmt->bindParam(":senha", $this->senha);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ========== FUNÇÃO UPDATE MODIFICADA ==========
    function update() {
        // Constrói a query dinamicamente
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, email = :email, contato = :contato, avatar_url = :avatar_url";
        
        // Adiciona a senha à query apenas se uma nova senha for fornecida
        if (!empty($this->senha)) {
            $query .= ", senha = :senha";
        }

        // Adiciona o role à query apenas se um novo role for fornecido
        if (!empty($this->role)) {
            $query .= ", role = :role";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Limpa e associa os parâmetros obrigatórios
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->contato=htmlspecialchars(strip_tags($this->contato));
        $this->avatar_url=htmlspecialchars(strip_tags($this->avatar_url));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':contato', $this->contato);
        $stmt->bindParam(':avatar_url', $this->avatar_url);
        $stmt->bindParam(':id', $this->id);

        // Associa a senha apenas se ela existir
        if (!empty($this->senha)) {
            $this->senha=password_hash(htmlspecialchars(strip_tags($this->senha)), PASSWORD_DEFAULT);
            $stmt->bindParam(':senha', $this->senha);
        }

        // Associa o role apenas se ele existir
        if (!empty($this->role)) {
            $this->role=htmlspecialchars(strip_tags($this->role));
            $stmt->bindParam(':role', $this->role);
        }

        // Executa a query
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