<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: *');

try {
    include_once '../../config/database.php';
    include_once '../../models/pedido.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro interno do servidor.'));
        exit;
    }
    
    $pedido = new Pedido($db);
    
    $input = file_get_contents("php://input");
    $data = json_decode($input);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array('message' => 'Dados JSON inválidos.'));
        exit;
    }
    
    // Validar se ID foi fornecido
    if (empty($data->id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do pedido é obrigatório.'));
        exit;
    }
    
    // Validar se ID é numérico
    if (!is_numeric($data->id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do pedido deve ser numérico.'));
        exit;
    }
    
    $pedido->id = intval($data->id);
    
    if ($pedido->delete()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Pedido deletado com sucesso.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível deletar o pedido.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
