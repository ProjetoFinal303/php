<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, POST'); // Permitir POST caso haja pre-flight
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

try {
    require_once '../../config/database.php';
    require_once '../../models/cliente.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(['message' => 'Erro interno do servidor (DB).']);
        exit;
    }
    
    $cliente = new Cliente($db);
    
    // CORREÇÃO: Ler o ID do parâmetro da URL (GET) em vez do body
    $id = $_GET['id'] ?? null;
    
    if (empty($id) || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['message' => 'ID do cliente é obrigatório e deve ser numérico (via URL).']);
        exit;
    }
    
    $cliente->id = $id;
    
    if ($cliente->delete()) {
        http_response_code(200);
        echo json_encode(['message' => 'Cliente deletado com sucesso.']);
    } else {
        http_response_code(503);
        echo json_encode(['message' => 'Não foi possível deletar o cliente.']);
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
?>
