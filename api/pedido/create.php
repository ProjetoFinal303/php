<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');

include_once '../../config/database.php';
include_once '../../models/pedido.php';

$database = new Database();
$db = $database->getConnection();
$pedido = new Pedido($db);
$data = json_decode(file_get_contents("php://input"));

$pedido->id_cliente = $data->id_cliente;
$pedido->descricao = $data->descricao;
$pedido->valor = $data->valor;

if($pedido->create()) {
    http_response_code(201);
    echo json_encode(array('message' => 'Pedido criado.', 'id_pedido' => $pedido->id));
} else {
    http_response_code(503);
    echo json_encode(array('message' => 'Não foi possível criar o pedido.'));
}
?>