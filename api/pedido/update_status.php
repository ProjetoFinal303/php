<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: *');

include_once '../../config/database.php';
include_once '../../models/pedido.php';

$database = new Database();
$db = $database->getConnection();
$pedido = new Pedido($db);
$data = json_decode(file_get_contents("php://input"));

$pedido->id = $data->id;
$pedido->status = $data->status;

if($pedido->update_status()) {
    http_response_code(200);
    echo json_encode(array('message' => 'Status do pedido atualizado.'));
} else {
    http_response_code(503);
    echo json_encode(array('message' => 'Não foi possível atualizar o status.'));
}
?>