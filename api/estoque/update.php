<?php
// Alteração: Aceita POST além de PUT e PATCH para atualização
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT, PATCH');
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
    
    // Verificar se está usando produto_id ou id
    if (!empty($data->produto_id)) {
        // Atualizar por produto_id
        if (empty($data->quantidade)) {
            http_response_code(400);
            echo json_encode(array('message' => 'Quantidade é obrigatória.'));
            exit;
        }
        
        $estoque->produto_id = $data->produto_id;
        $estoque->quantidade = $data->quantidade;
        
        if ($estoque->updateByProdutoId()) {
            http_response_code(200);
            echo json_encode(array('success' => true, 'message' => 'Estoque atualizado com sucesso.'));
        } else {
            http_response_code(503);
            echo json_encode(array('success' => false, 'message' => 'Não foi possível atualizar o estoque.'));
        }
    } elseif (!empty($data->id)) {
        // Atualizar por id
        if (empty($data->quantidade)) {
            http_response_code(400);
            echo json_encode(array('message' => 'Quantidade é obrigatória.'));
            exit;
        }
        
        $estoque->id = $data->id;
        if (!empty($data->produto_id)) {
            $estoque->produto_id = $data->produto_id;
        }
        $estoque->quantidade = $data->quantidade;
        
        if ($estoque->update()) {
            http_response_code(200);
            echo json_encode(array('success' => true, 'message' => 'Estoque atualizado com sucesso.'));
        } else {
            http_response_code(503);
            echo json_encode(array('success' => false, 'message' => 'Não foi possível atualizar o estoque.'));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('message' => 'ID ou produto_id é obrigatório.'));
        exit;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro no servidor: ' . $e->getMessage()));
}
?>
