<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT'); // Ou PATCH
header('Access-Control-Allow-Headers: *');

include_once '../../config/database.php';
include_once '../../models/cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);
$data = json_decode(file_get_contents("php://input"));

if(empty($data->id)) {
    http_response_code(400);
    echo json_encode(array('message' => 'ID do cliente é obrigatório.'));
    return;
}

$cliente->id = $data->id;

// Preenche o objeto cliente com os dados recebidos
$cliente->nome = isset($data->nome) ? $data->nome : null;
$cliente->email = isset($data->email) ? $data->email : null;
$cliente->contato = isset($data->contato) ? $data->contato : null;
$cliente->avatar_url = isset($data->avatar_url) ? $data->avatar_url : null;
$cliente->senha = !empty($data->senha) ? $data->senha : null;
$cliente->role = isset($data->role) ? $data->role : null; // Permite a alteração do role

// Primeiro, busca o cliente para garantir que ele existe e para não sobrescrever dados existentes com null
$cliente->readOne();

if(!$cliente->nome) {
    http_response_code(404);
    echo json_encode(array('message' => 'Cliente não encontrado.'));
    return;
}

// Sobrescreve apenas os campos que foram enviados no request
if(isset($data->nome)) $cliente->nome = $data->nome;
if(isset($data->email)) $cliente->email = $data->email;
if(isset($data->contato)) $cliente->contato = $data->contato;
if(isset($data->avatar_url)) $cliente->avatar_url = $data->avatar_url;
if(isset($data->role)) $cliente->role = $data->role;
if(!empty($data->senha)) $cliente->senha = $data->senha;

// Tenta atualizar
if($cliente->update()) { // Você precisará ajustar o método update no Model também
    http_response_code(200);
    echo json_encode(array('message' => 'Cliente atualizado.'));
} else {
    http_response_code(503);
    echo json_encode(array('message' => 'Não foi possível atualizar o cliente.'));
}
?>