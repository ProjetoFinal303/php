<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
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
    
    // Validar parâmetro produto_id usando ?? null
    $produto_id = $_GET['produto_id'] ?? null;
    
    if (empty($produto_id)) {
        http_response_code(400);
        echo json_encode(['message' => 'ID do produto é obrigatório.']);
        exit;
    }
    
    // Validar se produto_id é numérico
    if (!is_numeric($produto_id)) {
        http_response_code(400);
        echo json_encode(['message' => 'ID do produto deve ser numérico.']);
        exit;
    }
    
    $avaliacao = new Avaliacao($db);
    $avaliacao->produto_id = $produto_id;
    $stmt = $avaliacao->readByProduto();
    $num = $stmt->rowCount();
    
    if ($num > 0) {
        $avaliacoes_arr = array();
        $avaliacoes_arr['records'] = array();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $avaliacao_item = array(
                'id' => $id,
                'produto_id' => $produto_id,
                'cliente_id' => $cliente_id,
                'nota' => $nota,
                'comentario' => $comentario,
                'data_avaliacao' => $data_avaliacao
            );
            array_push($avaliacoes_arr['records'], $avaliacao_item);
        }
        
        http_response_code(200);
        echo json_encode($avaliacoes_arr);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Nenhuma avaliação encontrada para este produto.']);
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
