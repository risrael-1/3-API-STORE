<?php
namespace FwTest\Controller;

require_once __DIR__ . '/../classes/User.php';

use FwTest\Classes\User;

class UserController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    /**
     * @Route("/register")
     */
    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['username'], $input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Username and password required']);
            exit;
        }

        $id = $this->user->register($input['username'], $input['password']);
        echo json_encode(['message' => 'User registered', 'id' => $id]);
    }

    /**
     * @Route("/login")
     */
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['username'], $input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Username and password required']);
            exit;
        }

        $auth = $this->user->login($input['username'], $input['password']);
        if ($auth) {
            echo json_encode(['message' => 'Login successful']);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
    }

    /**
     * @Route("/logout")
     */
    public function logout() {
        $this->user->logout();
        echo json_encode(['message' => 'Logged out']);
    }

    /**
     * @Route("/me")
     */
    public function me() {
        if (!$this->user->check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $profile = $this->user->getById($_SESSION['user_id']);
        echo json_encode($profile);
    }
}
