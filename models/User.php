<?php
namespace FwTest\Classes;

require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $db = new \Database();
        $this->conn = $db->connect();

        // Démarrer la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Créer un nouvel utilisateur
    public function register($username, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hash);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    // Login
    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return $user;
        }
        return false;
    }

    // Logout
    public function logout() {
        session_destroy();
    }

    // Vérifie si l'utilisateur est connecté
    public function check() {
        return isset($_SESSION['user_id']);
    }

    // Récupérer l'utilisateur par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, username FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
