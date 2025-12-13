<?php

use FwTest\Classes\User;

header('Content-Type: application/json');

require_once __DIR__ . '/models/Store.php';
require_once __DIR__ . '/models/User.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Initialize models
$user = new User();
$store = new Store();

// --- USER ROUTES ---

// Register a new user
if ($uri === '/register' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['username'], $input['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Username and password required']);
        exit;
    }
    $id = $user->register($input['username'], $input['password']);
    echo json_encode(['message' => 'User registered', 'id' => $id]);
    exit;
}

// Login
if ($uri === '/login' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['username'], $input['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Username and password required']);
        exit;
    }
    $auth = $user->login($input['username'], $input['password']);
    if ($auth) {
        $_SESSION['user_id'] = $auth['id'];
        echo json_encode(['message' => 'Login successful']);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
    exit;
}

// Logout
if ($uri === '/logout' && $method === 'POST') {
    session_destroy();
    echo json_encode(['message' => 'Logged out']);
    exit;
}

// Get current user profile
if ($uri === '/me' && $method === 'GET') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    $profile = $user->getById($_SESSION['user_id']);
    echo json_encode($profile);
    exit;
}

// --- PROTECTED STORE ROUTES ---
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

switch ("$method:$uri") {

    // Get all stores
    case 'GET:/stores':
        echo json_encode($store->getAll());
        break;

    // Get single store by id
    case 'GET:/store':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Store ID is required']);
            break;
        }
        $result = $store->get($id);
        if ($result) {
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Store not found']);
        }
        break;

    // Create store
    case 'POST:/store':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['name'], $data['address'], $data['city'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            break;
        }
        $id = $store->create($data);
        http_response_code(201);
        echo json_encode(['message' => 'Store created', 'id' => $id]);
        break;

    // Update store
    case 'PUT:/store':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Store ID is required']);
            break;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $success = $store->update($id, $data);
        echo json_encode(['success' => $success]);
        break;

    // Delete store
    case 'DELETE:/store':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Store ID is required']);
            break;
        }
        $success = $store->delete($id);
        echo json_encode(['success' => $success]);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}
