<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include_once '../../config/database.php';
include_once '../../models/produto.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro interno do servidor.']);
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

try {
    if (!empty($data->id)) {
        $produto->id = $data->id;
        
        // Validar e atribuir campos com isset
        if (!isset($data->nome) || !isset($data->preco)) {
            http_response_code(400);
            echo json_encode(['message' => 'Nome e Preço são obrigatórios para atualizar.']);
            exit;
        }
        
        $produto->nome = $data->nome;
        $produto->descricao = isset($data->descricao) ? $data->descricao : '';
        $produto->preco = $data->preco;
        
        // CORREÇÃO: Atribuir $data->image_url (front-end) para $produto->imagem_url (modelo)
        $produto->imagem_url = isset($data->image_url) ? $data->image_url : '';
        
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
    echo json_encode(['error' => $e->getMessage()]);
}
?>
