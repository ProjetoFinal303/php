<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');

try {
    include_once '../../config/database.php';
    include_once '../../models/avaliacao.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro interno do servidor.'));
        exit;
    }
    
    $avaliacao = new Avaliacao($db);
    
    $input = file_get_contents("php://input");
    $data = json_decode($input);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array('message' => 'Dados JSON inválidos.'));
        exit;
    }
    
    if (!empty($data->produto_id) && !empty($data->cliente_id) && !empty($data->nota)) {
        $avaliacao->produto_id = $data->produto_id;
        $avaliacao->cliente_id = $data->cliente_id;
        $avaliacao->nota = $data->nota;
        $avaliacao->comentario = !empty($data->comentario) ? $data->comentario : '';
        
        if ($avaliacao->create()) {
            http_response_code(201);
            echo json_encode(array('message' => 'Avaliação enviada.'));
        } else {
            http_response_code(503);
            echo json_encode(array('message' => 'Não foi possível enviar a avaliação.'));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('message' => 'Dados incompletos.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
