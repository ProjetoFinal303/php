<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
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
            echo json_encode(array('message' => 'Avaliação criada com sucesso.'));
        } else {
            http_response_code(503);
            echo json_encode(array('message' => 'Não foi possível criar a avaliação.'));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('message' => 'Dados incompletos. Produto ID, Cliente ID e Nota são obrigatórios.'));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('error' => $e->getMessage()));
}
