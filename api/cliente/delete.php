<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: *');

try {
    include_once '../../config/database.php';
    include_once '../../models/cliente.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro interno do servidor.'));
        exit;
    }
    
    $cliente = new Cliente($db);
    
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
        echo json_encode(array('message' => 'ID do cliente é obrigatório.'));
        exit;
    }
    
    // Validar se ID é numérico
    if (!is_numeric($data->id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do cliente deve ser numérico.'));
        exit;
    }
    
    $cliente->id = intval($data->id);
    
    // Verificar se o cliente existe antes de tentar deletar
    if (!$cliente->read_single()) {
        http_response_code(404);
        echo json_encode(array('message' => 'Cliente não encontrado.'));
        exit;
    }
    
    if ($cliente->delete()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Cliente deletado com sucesso.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível deletar o cliente.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
