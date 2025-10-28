<?php
// Alteração: ID agora é recebido via $_GET (query string) ao invés de JSON no corpo da requisição
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

try {
    require_once '../../config/database.php';
    require_once '../../models/estoque.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(['message' => 'Erro interno do servidor (DB).']);
        exit;
    }
    
    $estoque = new Estoque($db);
    
    // Obter ID via $_GET ou $_REQUEST
    $id = $_REQUEST['id'] ?? null;
    
    // Validar parâmetro id
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['message' => 'Parâmetro id é obrigatório.']);
        exit;
    }
    
    $estoque->id = $id;
    
    if ($estoque->delete()) {
        http_response_code(200);
        echo json_encode(['message' => 'Estoque deletado com sucesso.']);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Erro ao deletar estoque.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro interno do servidor: ' . $e->getMessage()]);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro fatal: ' . $e->getMessage()]);
}
?>
