<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/estoque.php';

$database = new Database();
$db = $database->getConnection();

$estoque = new Estoque($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->produto_id) && !empty($data->quantidade)){
    $estoque->produto_id = $data->produto_id;
    $estoque->quantidade = $data->quantidade;
    
    if($estoque->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Estoque criado com sucesso."));
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "N\u00e3o foi poss\u00edvel criar o estoque."));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Dados incompletos. produto_id e quantidade s\u00e3o obrigat\u00f3rios."));
}
?>
