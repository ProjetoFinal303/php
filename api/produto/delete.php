<?php
// Headers necessários
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Incluir ficheiros de configuração e modelo
include_once '../../config/database.php';
include_once '../../models/produto.php';

// Instanciar objetos
$database = new Database();
$db = $database->getConnection();
$produto = new Produto($db);

// Obter dados enviados
$data = json_decode(file_get_contents("php://input"));

// Verificar se o ID foi enviado
if(!empty($data->id)) {
    // Definir o ID do produto a ser apagado
    $produto->id = $data->id;

    // Tentar apagar o produto
    if($produto->delete()) {
        // Resposta 200 - OK
        http_response_code(200);
        echo json_encode(array('message' => 'Produto apagado com sucesso.'));
    } else {
        // Resposta 503 - Serviço indisponível
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível apagar o produto.'));
    }
} else {
    // Resposta 400 - Pedido inválido
    http_response_code(400);
    echo json_encode(array('message' => 'ID do produto não fornecido.'));
}
?>
