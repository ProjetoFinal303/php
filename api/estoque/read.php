<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: *');

try {
    include_once '../../config/database.php';
    include_once '../../models/estoque.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro interno do servidor.'));
        exit;
    }
    
    $estoque = new Estoque($db);
    
    $result = $estoque->read();
    
    if (!$result) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro ao buscar itens do estoque.'));
        exit;
    }
    
    $num = $result->rowCount();
    
    if ($num > 0) {
        $estoque_arr = array();
        $estoque_arr["records"] = array();
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Filtrar dados para incluir apenas campos seguros
            $item = array(
                'id' => isset($row['id']) ? $row['id'] : null,
                'produto_id' => isset($row['produto_id']) ? $row['produto_id'] : null,
                'quantidade' => isset($row['quantidade']) ? $row['quantidade'] : null,
                'quantidade_minima' => isset($row['quantidade_minima']) ? $row['quantidade_minima'] : null,
                'data_ultima_atualizacao' => isset($row['data_ultima_atualizacao']) ? $row['data_ultima_atualizacao'] : null,
                'status' => isset($row['status']) ? $row['status'] : null
            );
            
            array_push($estoque_arr["records"], $item);
        }
        
        http_response_code(200);
        echo json_encode($estoque_arr);
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'Nenhum item no estoque.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
