<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../../config/database.php';
include_once '../../models/cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);

$result = $cliente->read();
$num = $result->rowCount();

if($num > 0) {
    $clientes_arr = array();
    $clientes_arr["records"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($clientes_arr["records"], $row);
    }
    http_response_code(200);
    echo json_encode($clientes_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'Nenhum cliente encontrado.'));
}
?>