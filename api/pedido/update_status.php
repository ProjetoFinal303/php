<?php
// Alteração: Aceita POST além de PUT para atualização de status
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: *');

try {
    include_once '../../config/database.php';
    include_once '../../models/pedido.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro interno do servidor (DB).'));
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
    
    // Validar dados
    if (empty($data->id) || !isset($data->status)) {
        http_response_code(400);
        echo json_encode(array('message' => 'Dados incompletos. ID e Status são obrigatórios.'));
        exit;
    }
    
    $pedido->id = $data->id;
    $pedido->status = $data->status;
    
    if($pedido->update_status()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Status do pedido atualizado.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível atualizar o status.'));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.', 'error' => $e->getMessage()));
    error_log($e->getMessage());
}
?>
