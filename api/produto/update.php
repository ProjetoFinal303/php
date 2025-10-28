<?php
// Correção automática: Adicionado tratamento de erros e mapeamento consistente de image_url/imagem_url
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
// CORREÇÃO: Permitir POST, que é como o script.js está enviando agora
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
    
    $input = file_get_contents("php://input");
    $data = json_decode($input);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['message' => 'Dados JSON inválidos.']);
        exit;
    }
    
    if (!empty($data->id)) {
        $produto->id = $data->id;
        
        if (!isset($data->nome) || !isset($data->preco)) {
            http_response_code(400);
            echo json_encode(['message' => 'Nome e Preço são obrigatórios para atualizar.']);
            exit;
        }
        
        $produto->nome = $data->nome;
        $produto->descricao = isset($data->descricao) ? $data->descricao : '';
        $produto->preco = $data->preco;
        $produto->imagem_url = isset($data->image_url) ? $data->image_url : (isset($data->imagem_url) ? $data->imagem_url : '');
        $produto->stripe_price_id = isset($data->stripe_price_id) ? $data->stripe_price_id : '';
        
        if ($produto->update()) {
            http_response_code(200);
            echo json_encode(['message' => 'Produto atualizado com sucesso.']);
        } else {
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
