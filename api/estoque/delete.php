<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

try {
    include_once '../../config/database.php';
    include_once '../../models/estoque.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro interno do servidor (DB).'));
        exit;
    }
    
    $estoque = new Estoque($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array('message' => 'Dados JSON inválidos.'));
        exit;
    }
    
    // Validar parâmetro id
    if (empty($data->id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do estoque não fornecido.'));
        exit;
    }
    
    // Validar se id é numérico
    if (!is_numeric($data->id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do estoque deve ser numérico.'));
        exit;
    }
    
    $estoque->id = intval($data->id);
    
    if ($estoque->delete()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Estoque deletado com sucesso.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível deletar o estoque.'));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.', 'error' => $e->getMessage()));
    error_log($e->getMessage());
}
?>
