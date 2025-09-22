<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');

include_once '../../config/database.php';
include_once '../../models/cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->nome) && !empty($data->email) && !empty($data->senha)) {
    $cliente->nome = $data->nome;
    $cliente->email = $data->email;
    $cliente->contato = $data->contato;
    $cliente->senha = $data->senha;

    if($cliente->create()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Cliente criado com sucesso.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível criar o cliente.'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Dados incompletos.'));
}
?>