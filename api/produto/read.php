<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../../config/database.php';
include_once '../../models/produto.php';

$database = new Database();
$db = $database->getConnection();
$produto = new Produto($db);
$result = $produto->read();
$num = $result->rowCount();

if($num > 0) {
    $produtos_arr = array();
    $produtos_arr["records"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        array_push($produtos_arr["records"], $row);
    }
    http_response_code(200);
    echo json_encode($produtos_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'Nenhum produto encontrado.'));
}
?>