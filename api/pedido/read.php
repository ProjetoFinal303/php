<?php
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
$stmt = $pedido->read();

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar pedidos']);
    exit;
}

$pedidos = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pedidos[] = $row;
}

echo json_encode($pedidos);
?>
