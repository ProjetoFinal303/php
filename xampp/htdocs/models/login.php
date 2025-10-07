<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');

include_once '../../config/database.php';
include_once '../../models/cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->senha)) {
    $cliente->email = $data->email;
    $cliente->readOne('email'); // Método precisa ser criado ou ajustado no Model para buscar por email

    if($cliente->id && password_verify($data->senha, $cliente->senha)) {
        http_response_code(200);
        $user_data = array(
            "id" => $cliente->id,
            "nome" => $cliente->nome,
            "email" => $cliente->email,
            "contato" => $cliente->contato,
            "avatar_url" => $cliente->avatar_url,
            "role" => $cliente->role
        );
        echo json_encode($user_data);
    } else {
        http_response_code(401);
        echo json_encode(array('message' => 'Login falhou. Email ou senha incorretos.'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Email e senha são obrigatórios.'));
}
?>