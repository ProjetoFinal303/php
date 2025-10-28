<?php
// Correção automática: header + tratamento JSON + suppression de erro + compatibilidade image_url/imagem_url

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
// CORREÇÃO: Permitir POST, que é como o script.js está enviando agora
header('Access-Control-Allow-Methods: POST, PUT, PATCH');
header('Access-Control-Allow-Headers: *');

try {
    include_once '../../config/database.php';
    include_once '../../models/cliente.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array('message' => 'Erro interno do servidor (DB).'));
        exit;
    }
    
    $cliente = new Cliente($db);
    
    $input = file_get_contents("php://input");
    $data = json_decode($input);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array('message' => 'Dados JSON inválidos.'));
        exit;
    }
    
    if (empty($data->id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do cliente é obrigatório.'));
        exit;
    }
    
    if (!is_numeric($data->id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do cliente deve ser numérico.'));
        exit;
    }
    
    $cliente->id = intval($data->id);
    
    if (!$cliente->readOne() || !$cliente->nome) {
        http_response_code(404);
        echo json_encode(array('message' => 'Cliente não encontrado.'));
        exit;
    }
    
    if (isset($data->nome) && !empty(trim($data->nome))) {
        $cliente->nome = trim(htmlspecialchars($data->nome));
    }
    
    if (isset($data->email) && !empty(trim($data->email))) {
        if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(array('message' => 'Formato de email inválido.'));
            exit;
        }
        $cliente->email = trim(strtolower($data->email));
    }
    
    if (isset($data->contato)) {
        $cliente->contato = !empty($data->contato) ? trim(htmlspecialchars($data->contato)) : '';
    }
    
    if (isset($data->avatar_url)) {
        $cliente->avatar_url = !empty($data->avatar_url) ? trim($data->avatar_url) : null;
    }
    
    if (isset($data->senha) && !empty($data->senha)) {
        if (strlen($data->senha) < 6) {
            http_response_code(400);
            echo json_encode(array('message' => 'A senha deve ter pelo menos 6 caracteres.'));
            exit;
        }
        $cliente->senha = password_hash($data->senha, PASSWORD_DEFAULT);
    }
    
    if ($cliente->update()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Cliente atualizado com sucesso.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível atualizar o cliente.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.', 'error' => $e->getMessage()));
    error_log($e->getMessage());
}
?>
