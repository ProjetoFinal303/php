<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../../config/database.php';
include_once '../../models/cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);
$cliente->id = isset($_GET['id']) ? $_GET['id'] : die();
$cliente->readOne();

if($cliente->nome != null) {
    $cliente_arr = array(
        "id" => $cliente->id,
        "nome" => $cliente->nome,
        "email" => $cliente->email,
        "contato" => $cliente->contato,
        "avatar_url" => $cliente->avatar_url,
        "role" => $cliente->role
    );
    http_response_code(200);
    echo json_encode($cliente_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Cliente não encontrado."));
}
?>