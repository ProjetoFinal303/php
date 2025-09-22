<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../../config/database.php';
include_once '../../models/ingrediente.php';

$database = new Database();
$db = $database->getConnection();
$ingrediente = new Ingrediente($db);
$result = $ingrediente->read();
$num = $result->rowCount();

if($num > 0) {
    $ingredientes_arr = array("records" => array());
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($ingredientes_arr["records"], $row);
    }
    http_response_code(200);
    echo json_encode($ingredientes_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'Nenhum ingrediente encontrado.'));
}
?>