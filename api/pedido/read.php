<?php
header('Access-Control-Allow-Origin: *'); // Adicionado para consistência
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// inclui banco e modelo
include_once '../../config/database.php';
include_once '../../models/pedido.php';

$database = new Database();
$db = $database->getConnection();

$pedido = new Pedido($db);

try {
    $stmt = $pedido->read();
    
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao buscar pedidos']);
        exit;
    }

    // *** INÍCIO DA MODIFICAÇÃO ***
    // Verificar se há resultados
    $num = $stmt->rowCount();

    if ($num > 0) {
        $pedidos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pedidos[] = $row;
        }
        
        // Retornar 200 OK com os pedidos
        http_response_code(200);
        // Padronizar a resposta para corresponder aos outros endpoints 'read'
        echo json_encode(array("records" => $pedidos));
    } else {
        // Se não houver pedidos, retornar 404 com uma mensagem
        http_response_code(404);
        echo json_encode(array('message' => 'Nenhum pedido encontrado.'));
    }
    // *** FIM DA MODIFICAÇÃO ***

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
