<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');

include_once '../../config/database.php';
include_once '../../models/avaliacao.php';

$database = new Database();
$db = $database->getConnection();
$avaliacao = new Avaliacao($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->produto_id) && !empty($data->cliente_id) && !empty($data->nota)) {
    $avaliacao->produto_id = $data->produto_id;
    $avaliacao->cliente_id = $data->cliente_id;
    $avaliacao->nota = $data->nota;
    $avaliacao->comentario = !empty($data->comentario) ? $data->comentario : '';

    if($avaliacao->create()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Avaliação enviada.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível enviar a avaliação.'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Dados incompletos.'));
}
?>