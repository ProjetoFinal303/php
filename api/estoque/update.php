<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT, PATCH');
header('Access-Control-Allow-Headers: *');

try {
    include_once '../../config/database.php';
    include_once '../../models/estoque.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro interno do servidor.'));
        exit;
    }
    
    $estoque = new Estoque($db);
    
    $input = file_get_contents("php://input");
    $data = json_decode($input);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array('message' => 'Dados JSON inválidos.'));
        exit;
    }
    
    // Validar campos obrigatórios
    if (empty($data->id_produto)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do produto é obrigatório.'));
        exit;
    }
    
    if (!isset($data->quantidade)) {
        http_response_code(400);
        echo json_encode(array('message' => 'Quantidade é obrigatória.'));
        exit;
    }
    
    // Validar tipos de dados
    if (!is_numeric($data->id_produto)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do produto deve ser numérico.'));
        exit;
    }
    
    if (!is_numeric($data->quantidade)) {
        http_response_code(400);
        echo json_encode(array('message' => 'Quantidade deve ser numérica.'));
        exit;
    }
    
    // Validar regras de negócio
    $quantidade = floatval($data->quantidade);
    if ($quantidade < 0) {
        http_response_code(400);
        echo json_encode(array('message' => 'Quantidade não pode ser negativa.'));
        exit;
    }
    
    if ($quantidade > 999999) {
        http_response_code(400);
        echo json_encode(array('message' => 'Quantidade muito alta (máximo: 999999).'));
        exit;
    }
    
    // Sanitizar e atribuir dados
    $estoque->id_produto = intval($data->id_produto);
    $estoque->quantidade = $quantidade;
    
    // Atualizar quantidade mínima se fornecida
    if (isset($data->quantidade_minima) && is_numeric($data->quantidade_minima)) {
        $quantidade_minima = floatval($data->quantidade_minima);
        if ($quantidade_minima >= 0 && $quantidade_minima <= 999999) {
            $estoque->quantidade_minima = $quantidade_minima;
        }
    }
    
    if ($estoque->update()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Estoque atualizado com sucesso.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível atualizar o estoque.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
