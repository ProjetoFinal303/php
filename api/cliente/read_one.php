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
    
    // Validar se ID foi fornecido
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do cliente é obrigatório.'));
        exit;
    }
    
    // Validar se ID é numérico
    if (!is_numeric($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do cliente deve ser numérico.'));
        exit;
    }
    
    $cliente->id = intval($_GET['id']);
    
    if ($cliente->readOne()) {
        // Verificar se o cliente foi encontrado
        if ($cliente->nome != null) {
            $cliente_arr = array(
                "id" => $cliente->id,
                "nome" => $cliente->nome,
                "email" => $cliente->email,
                "contato" => $cliente->contato,
                "avatar_url" => $cliente->avatar_url,
                "role" => $cliente->role
                // senha removida por segurança - NUNCA retornar em APIs
            );
            
            http_response_code(200);
            echo json_encode($cliente_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Cliente não encontrado."));
        }
    } else {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro ao buscar cliente.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
