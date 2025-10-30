<?php
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');

include_once '../../config/database.php';
include_once '../../models/pedido.php';
include_once '../../models/estoque.php';

$database = new Database();
$db = $database->getConnection();

$pedido = new Pedido($db);
$estoque = new Estoque($db);

$data = json_decode(file_get_contents("php://input"));

// Validar campos obrigatórios
if(empty($data->cliente_id) || empty($data->produto_id) || empty($data->quantidade) || empty($data->preco_total)) {
    http_response_code(400);
    echo json_encode(array('message' => 'Dados incompletos. Campos obrigatórios: cliente_id, produto_id, quantidade, preco_total'));
    exit();
}

$pedido->cliente_id = $data->cliente_id;
$pedido->produto_id = $data->produto_id;
$pedido->quantidade = $data->quantidade;
$pedido->preco_total = $data->preco_total;
$pedido->status = isset($data->status) ? $data->status : 'pendente';

if($pedido->create()) {
    // Atualizar o estoque (decrementar a quantidade)
    // Primeiro, buscar a quantidade atual
    $query_estoque = "SELECT quantidade FROM estoque WHERE produto_id = :produto_id";
    $stmt = $db->prepare($query_estoque);
    $stmt->bindParam(':produto_id', $data->produto_id);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $quantidade_atual = $row['quantidade'];
        $nova_quantidade = $quantidade_atual - $data->quantidade;
        
        // Atualizar o estoque
        $estoque->produto_id = $data->produto_id;
        $estoque->quantidade = $nova_quantidade;
        $estoque->updateByProdutoId();
    }
    
    http_response_code(201);
    echo json_encode(array('success' => true, 'message' => 'Pedido criado com sucesso.'));
} else {
    http_response_code(503);
    echo json_encode(array('success' => false, 'message' => 'Não foi possível criar o pedido.'));
}
?>
