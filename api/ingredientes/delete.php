<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: *');
include_once '../../config/database.php';
include_once '../../models/ingrediente.php';

$database = new Database();
$db = $database->getConnection();
$ingrediente = new Ingrediente($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
    $ingrediente->id = $data->id;
    if($ingrediente->delete()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Ingrediente apagado.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível apagar o ingrediente.'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'ID do ingrediente não fornecido.'));
}
?>