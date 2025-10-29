<?php
echo 'Início<br>';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Alteração: Aceita POST e padroniza leitura de array
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

try {
    include_once '../../config/database.php';
    include_once '../../models/produto.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(['message' => 'Erro interno do servidor (DB).']);
        exit;
    }
    
    $produto = new Produto($db);
    
    // CORREÇÃO: Usando 'true' para decodificar como array, igual ao delete.php
    $input = file_get_contents("php://input");
    echo 'Após file_get_contents<br>';
    $data = json_decode($input, true); 
    echo 'Após json_decode<br>';
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['message' => 'Dados JSON inválidos.']);
        exit;
    }
    // Adiciona log para depuração, como sugerido no seu resumo
    // Verifique este log no seu servidor (ex: /opt/lampp/logs/error_log)
    error_log("UPDATE.PHP RECEBIDO: " . json_encode($data));
    
    // CORREÇÃO: Acessando como array
    if (!empty($data['id'])) {
        $produto->id = $data['id'];
        
        if (!isset($data['nome']) || !isset($data['preco'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Nome e Preço são obrigatórios para atualizar.']);
            exit;
        }
        
        $produto->nome = $data['nome'];
        $produto->descricao = isset($data['descricao']) ? $data['descricao'] : '';
        $produto->preco = $data['preco'];
        
        // CORREÇÃO: Simplificado para ler 'imagem_url'
        $produto->imagem_url = isset($data['imagem_url']) ? $data['imagem_url'] : '';
        $produto->stripe_price_id = isset($data['stripe_price_id']) ? $data['stripe_price_id'] : '';
        
        if ($produto->update()) {
            http_response_code(200);
            echo json_encode(['message' => 'Produto atualizado com sucesso.']);
        } else {
            // Log de erro se o update falhar no modelo
            error_log("UPDATE.PHP FALHOU: Erro ao executar \$produto->update() para ID: " . $data['id']);
            http_response_code(503);
            echo json_encode(['message' => 'Não foi possível atualizar o produto.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'ID do produto não fornecido.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro interno do servidor.', 'error' => $e->getMessage()]);
    error_log($e->getMessage());
}
?>
