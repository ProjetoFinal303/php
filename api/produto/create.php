<?php
// Headers necessários
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Incluir ficheiros de configuração e modelo
include_once '../../config/database.php';
include_once '../../models/produto.php';

// Instanciar objetos
$database = new Database();
$db = $database->getConnection();
$produto = new Produto($db);

// Obter dados enviados via POST
$data = json_decode(file_get_contents("php://input"));

// Verificar se os dados mínimos foram enviados
if(
    !empty($data->nome) &&
    !empty($data->preco)
) {
    // Definir valores das propriedades do produto
    $produto->nome = $data->nome;
    $produto->descricao = isset($data->descricao) ? $data->descricao : '';
    $produto->preco = $data->preco;
    $produto->image_url = isset($data->image_url) ? $data->image_url : '';
    $produto->stripe_price_id = isset($data->stripe_price_id) ? $data->stripe_price_id : '';

    // Tentar criar o produto
    if($produto->create()) {
        // Resposta 201 - Criado
        http_response_code(201);
        echo json_encode(array('message' => 'Produto criado com sucesso.'));
    } else {
        // Resposta 503 - Serviço indisponível
        http_response_code(503);
        echo json_encode(array('message' => 'Não foi possível criar o produto.'));
    }
} else {
    // Resposta 400 - Pedido inválido
    http_response_code(400);
    echo json_encode(array('message' => 'Dados incompletos. Nome e preço são obrigatórios.'));
}
?>
