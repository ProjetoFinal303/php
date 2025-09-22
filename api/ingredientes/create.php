<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');
include_once '../../config/database.php';
include_once '../../models/ingrediente.php';

$database = new Database();
$db = $database->getConnection();
$ingrediente = new Ingrediente($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->nome)) {
    $ingrediente->nome = $data->nome;
    if($ingrediente->create()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Ingrediente criado.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível criar o ingrediente.'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Dados incompletos.'));
}
?>