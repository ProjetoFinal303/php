<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');

include_once '../../config/database.php';
include_once '../../models/estoque.php';

$database = new Database();
$db = $database->getConnection();
$estoque = new Estoque($db);
$data = json_decode(file_get_contents("php://input"));

$estoque->id_produto = $data->id_produto;
$estoque->quantidade = $data->quantidade;

if($estoque->update()) {
    http_response_code(200);
    echo json_encode(array('message' => 'Estoque atualizado.'));
} else {
    http_response_code(503);
    echo json_encode(array('message' => 'Não foi possível atualizar o estoque.'));
}
?>