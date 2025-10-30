<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include_once '../../config/database.php';
include_once '../../models/produto.php';

$database = new Database();
$db = $database->getConnection();
$produto = new Produto($db);

try {
    $result = $produto->read();
    $num = $result->rowCount();

    if($num > 0) {
        $produtos_arr = [];
        $produtos_arr["records"] = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($produtos_arr["records"], $row);
        }
        http_response_code(200);
        echo json_encode($produtos_arr);
    } else {
        // *** CORREÇÃO APLICADA ***
        // Retorna 200 OK com a mensagem personalizada
        http_response_code(200);
        echo json_encode(['records' => [], 'message' => 'Nenhum produto cadastrado ainda.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
