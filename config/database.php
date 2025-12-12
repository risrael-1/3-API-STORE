<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'wshop';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Méthode pour obtenir la connexion
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            // Configurer PDO pour lancer des exceptions en cas d'erreur
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // En cas d'erreur, retourner un message JSON et stopper le script
            echo json_encode(['error' => 'Connection Error: ' . $e->getMessage()]);
            exit;
        }

        return $this->conn;
    }
}
