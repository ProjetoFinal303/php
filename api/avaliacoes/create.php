<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

try {
    require_once '../../config/database.php';
    require_once '../../models/avaliacao.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(['message' => 'Erro interno do servidor (DB).']);
        exit;
    }
    
    $avaliacao = new Avaliacao($db);
    
    // Obter e decodificar o input JSON
    $input = file_get_contents("php://input");
    $data = json_decode($input);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['message' => 'Dados JSON inválidos.']);
        exit;
    }
    
    // Validar parâmetros usando ?? null DENTRO do try
    $produto_id = $data->produto_id ?? null;
    $cliente_id = $data->cliente_id ?? null;
    $nota = $data->nota ?? null;
    
    if (empty($produto_id) || empty($cliente_id) || empty($nota)) {
        http_response_code(400);
        echo json_encode(['message' => 'Produto ID, Cliente ID e Nota são obrigatórios.']);
        exit;
    }
    
    // Validar se são numéricos
    if (!is_numeric($produto_id) || !is_numeric($cliente_id) || !is_numeric($nota)) {
        http_response_code(400);
        echo json_encode(['message' => 'IDs e nota devem ser numéricos.']);
        exit;
    }
    
    $avaliacao->produto_id = $produto_id;
    $avaliacao->cliente_id = $cliente_id;
    $avaliacao->nota = $nota;
    $avaliacao->comentario = $data->comentario ?? '';
    
    if ($avaliacao->create()) {
        http_response_code(201);
        echo json_encode(['message' => 'Avaliação criada com sucesso.']);
    } else {
        http_response_code(503);
        echo json_encode(['message' => 'Não foi possível criar a avaliação.']);
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
