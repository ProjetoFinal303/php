<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../../config/database.php';
include_once '../../models/pedido.php';

$database = new Database();
$db = $database->getConnection();
$pedido = new Pedido($db);
$result = $pedido->read();
$num = $result->rowCount();

if($num > 0) {
    $pedidos_arr = array();
    $pedidos_arr["records"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        array_push($pedidos_arr["records"], $row);
    }
    http_response_code(200);
    echo json_encode($pedidos_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'Nenhum pedido encontrado.'));
}
?>