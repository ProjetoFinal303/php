<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../../config/database.php';
include_once '../../models/estoque.php';

$database = new Database();
$db = $database->getConnection();
$estoque = new Estoque($db);
$result = $estoque->read();
$num = $result->rowCount();

if($num > 0) {
    $estoque_arr = array();
    $estoque_arr["records"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        array_push($estoque_arr["records"], $row);
    }
    http_response_code(200);
    echo json_encode($estoque_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'Nenhum item no estoque.'));
}
?>