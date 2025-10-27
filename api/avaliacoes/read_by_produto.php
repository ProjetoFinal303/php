<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
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
    
    // Validar parâmetro produto_id
    if (!isset($_GET['produto_id']) || empty($_GET['produto_id'])) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do produto é obrigatório.'));
        exit;
    }
    
    // Validar se produto_id é numérico
    if (!is_numeric($_GET['produto_id'])) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do produto deve ser numérico.'));
        exit;
    }
    
    $avaliacao = new Avaliacao($db);
    $avaliacao->produto_id = intval($_GET['produto_id']);
    
    $result = $avaliacao->readByProduto();
    
    if (!$result) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro ao buscar avaliações.'));
        exit;
    }
    
    $num = $result->rowCount();
    
    if ($num > 0) {
        $avaliacoes_arr = array();
        $avaliacoes_arr["records"] = array();
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            
            $item = array(
                'id' => $id,
                'nota' => $nota,
                'comentario' => $comentario,
                'created_at' => $created_at,
                'nome_cliente' => $nome_cliente,
                'avatar_url' => $avatar_url
            );
            
            array_push($avaliacoes_arr["records"], $item);
        }
        
        http_response_code(200);
        echo json_encode($avaliacoes_arr);
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'Nenhuma avaliação encontrada.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
