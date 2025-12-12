<?php
header('Content-Type: application/json');
require_once __DIR__ . '/models/Store.php';

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Simple routing
if ($method === 'POST' && $_SERVER['REQUEST_URI'] === '/stores') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['name'], $input['address'], $input['city'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $store = new Store();
    $id = $store->create($input);

    http_response_code(201);
    echo json_encode(['message' => 'Store created', 'id' => $id]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}
