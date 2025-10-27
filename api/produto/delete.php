<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
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
    
    $data = json_decode(file_get_contents("php://input"));
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['message' => 'Dados JSON inválidos.']);
        exit;
    }

    if(!empty($data->id)) {
        $produto->id = $data->id;
        if($produto->delete()) {
            http_response_code(200);
            echo json_encode(['message' => 'Produto apagado com sucesso.']);
        } else {
            http_response_code(503);
            echo json_encode(['message' => 'Não foi possível apagar o produto.']);
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
