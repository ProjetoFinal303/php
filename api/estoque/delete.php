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

if(!empty($data->id)){
    $estoque->id = $data->id;
    
    if($estoque->delete()){
        http_response_code(200);
        echo json_encode(array("message" => "Estoque deletado com sucesso."));
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "N\u00e3o foi poss\u00edvel deletar o estoque."));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Dados incompletos. ID \u00e9 obrigat\u00f3rio."));
}
?>
