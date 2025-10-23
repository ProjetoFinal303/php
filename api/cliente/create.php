<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
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
    
    $input = file_get_contents("php://input");
    $data = json_decode($input);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array('message' => 'Dados JSON inválidos.'));
        exit;
    }
    
    // Validar campos obrigatórios
    if (empty($data->nome) || empty($data->email) || empty($data->senha)) {
        http_response_code(400);
        echo json_encode(array('message' => 'Nome, email e senha são obrigatórios.'));
        exit;
    }
    
    // Validar formato do email
    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array('message' => 'Formato de email inválido.'));
        exit;
    }
    
    // Validar tamanho da senha (mínimo 6 caracteres)
    if (strlen($data->senha) < 6) {
        http_response_code(400);
        echo json_encode(array('message' => 'A senha deve ter pelo menos 6 caracteres.'));
        exit;
    }
    
    // Sanitizar e atribuir dados
    $cliente->nome = trim(htmlspecialchars($data->nome));
    $cliente->email = trim(strtolower($data->email));
    $cliente->contato = !empty($data->contato) ? trim(htmlspecialchars($data->contato)) : '';
    
    // Hash da senha para segurança
    $cliente->senha = password_hash($data->senha, PASSWORD_DEFAULT);
    
    if ($cliente->create()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Cliente criado com sucesso.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível criar o cliente.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
