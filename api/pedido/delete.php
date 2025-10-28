<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

try {
    require_once '../../config/database.php';
    require_once '../../models/pedido.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(['message' => 'Erro interno do servidor (DB).']);
        exit;
    }
    
    $pedido = new Pedido($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['message' => 'Dados JSON inválidos.']);
        exit;
    }
    
    // Validar parâmetro id
    $id = $data->id ?? null;
    
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['message' => 'ID do pedido é obrigatório.']);
        exit;
    }
    
    $pedido->id = $id;
    
    if ($pedido->delete()) {
        http_response_code(200);
        echo json_encode(['message' => 'Pedido deletado com sucesso.']);
    } else {
        http_response_code(503);
        echo json_encode(['message' => 'Não foi possível deletar o pedido.']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro: ' . $e->getMessage()]);
    exit;
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro fatal: ' . $e->getMessage()]);
    exit;
}
