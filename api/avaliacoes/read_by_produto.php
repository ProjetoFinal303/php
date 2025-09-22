<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../../config/database.php';
include_once '../../models/avaliacao.php';

$database = new Database();
$db = $database->getConnection();
$avaliacao = new Avaliacao($db);
$avaliacao->produto_id = isset($_GET['produto_id']) ? $_GET['produto_id'] : die();

$result = $avaliacao->readByProduto();
$num = $result->rowCount();

if($num > 0) {
    $avaliacoes_arr = array();
    $avaliacoes_arr["records"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $item = array(
            'id' => $id,
            'nota' => $nota,
            'comentario' => $comentario,
            'created_at' => $created_at,
            'nome_cliente' => $nome_cliente,
            'avatar_url' => $avatar_url
        );
        array_push($avaliacoes_arr["records"], $item);
    }
    http_response_code(200);
    echo json_encode($avaliacoes_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'Nenhuma avaliação encontrada.'));
}
?>