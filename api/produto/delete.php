<?php
// Alteração: Aceita POST com JSON body para receber o id
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE, GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

try {
    require_once '../../config/database.php';
    require_once '../../models/produto.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(['message' => 'Erro interno do servidor (DB).']);
        exit;
    }
    
    $produto = new Produto($db);
    
    // Obter ID do JSON body (POST)
    $id = null;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true); // 'true' para array
        
        // Adiciona log para depuração
        error_log("DELETE.PHP RECEBIDO: " . json_encode($data));

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['message' => 'JSON inválido: ' . json_last_error_msg()]);
            exit;
        }
        
        $id = $data['id'] ?? null;
    } else {
        // Para DELETE/GET, aceita query string (compatibilidade)
        $id = $_REQUEST['id'] ?? null;
        error_log("DELETE.PHP (GET/REQUEST) RECEBIDO: " . $id);
    }
    
    // Validar parâmetro id
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['message' => 'Parâmetro id é obrigatório.']);
        exit;
    }
    
    $produto->id = $id;
    
    if ($produto->delete()) {
        http_response_code(200);
        echo json_encode(['message' => 'Produto deletado com sucesso.']);
    } else {
        error_log("DELETE.PHP FALHOU: Erro ao executar \$produto->delete() para ID: " . $id);
        http_response_code(400);
        echo json_encode(['message' => 'Erro ao deletar produto.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro interno do servidor: ' . $e->getMessage()]);
}
?>
