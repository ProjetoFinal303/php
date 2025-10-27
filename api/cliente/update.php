<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, PATCH');
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
    
    // Validar se ID foi fornecido
    if (empty($data->id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do cliente é obrigatório.'));
        exit;
    }
    
    // Validar se ID é numérico
    if (!is_numeric($data->id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID do cliente deve ser numérico.'));
        exit;
    }
    
    $cliente->id = intval($data->id);
    
    // Verificar se cliente existe
    if (!$cliente->readOne() || !$cliente->nome) {
        http_response_code(404);
        echo json_encode(array('message' => 'Cliente não encontrado.'));
        exit;
    }
    
    // Atualizar campos fornecidos com validação
    if (isset($data->nome) && !empty(trim($data->nome))) {
        $cliente->nome = trim(htmlspecialchars($data->nome));
    }
    
    if (isset($data->email) && !empty(trim($data->email))) {
        // Validar formato do email
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
    
    // CRÍTICO: Tratar senha com segurança (HASH)
    if (isset($data->senha) && !empty($data->senha)) {
        // Validar tamanho mínimo da senha
        if (strlen($data->senha) < 6) {
            http_response_code(400);
            echo json_encode(array('message' => 'A senha deve ter pelo menos 6 caracteres.'));
            exit;
        }
        // SEGURANÇA: Hash da senha - NUNCA armazenar em texto plano
        $cliente->senha = password_hash($data->senha, PASSWORD_DEFAULT);
    }
    
    // Nota: Role não é atualizado por segurança - requer autorização especial
    // TODO: Implementar autenticação/autorização para mudanças de role
    
    if ($cliente->update()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Cliente atualizado com sucesso.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível atualizar o cliente.'));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('message' => 'Erro interno do servidor.'));
    error_log($e->getMessage());
}
?>
