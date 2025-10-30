<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: *');

try {
    include_once '../../config/database.php';
    include_once '../../models/cliente.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro interno do servidor.'));
        exit;
    }
    
    $cliente = new Cliente($db);
    
    $result = $cliente->read();
    
    if (!$result) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro ao buscar clientes.'));
        exit;
    }
    
    $num = $result->rowCount();
    
    if ($num > 0) {
        $clientes_arr = array();
        $clientes_arr["records"] = array();
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = array(
                'id' => $row['id'],
                'nome' => $row['nome'],
                'email' => $row['email'],
                'contato' => $row['contato'],
                'created_at' => isset($row['created_at']) ? $row['created_at'] : null
            );
            
            array_push($clientes_arr["records"], $item);
        }
        
        http_response_code(200);
        echo json_encode($clientes_arr);
    } else {
        // *** CORREÇÃO APLICADA ***
        // Retorna 200 OK com a mensagem personalizada
        http_response_code(200);
        echo json_encode(array('records' => [], 'message' => 'Sem clientes cadastrados ainda.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
