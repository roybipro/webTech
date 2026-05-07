<?php
header('Content-Type: application/json');

require_once __DIR__ . '/controller/BookController.php';

$controller = new BookController();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$body = [];

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $jsonBody = json_decode($rawInput, true);
    $body = is_array($jsonBody) ? $jsonBody : $_POST;
}

switch ($action) {
    case 'create':
        $response = $controller->create($body);
        break;

    case 'read_all':
        $response = $controller->readAll([
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? '',
            'status' => $_GET['status'] ?? ''
        ]);
        break;

    case 'read_one':
        $response = $controller->readOne((int) ($_GET['id'] ?? 0));
        break;

    case 'update':
        $response = $controller->update($body);
        break;

    case 'delete':
        $response = $controller->delete((int) ($body['id'] ?? 0));
        break;

    case 'categories':
        $response = $controller->categories();
        break;

    default:
        http_response_code(400);
        $response = ['success' => false, 'message' => 'Invalid action.'];
        break;
}

echo json_encode($response);
?>
