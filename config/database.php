<?php
class Database {
    // Credenciais do banco de dados local (XAMPP) com a porta atualizada
    private $host = "localhost:3306"; // A ÚNICA MUDANÇA É AQUI
    private $db_name = "lancho_db"; // Nome do banco de dados que você criou
    private $username = "root";
    private $password = ""; // Senha vazia por padrão no XAMPP
    public $conn;
    
    // Obtém a conexão com o banco de dados
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => false, "message" => "Erro de conexão: " . $exception->getMessage()));
            exit();
        }
        return $this->conn;
    }
}
?>
