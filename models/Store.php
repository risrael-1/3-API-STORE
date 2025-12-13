<?php
require_once __DIR__ . '/../config/database.php';

class Store {
    private $conn;
    private $table = 'stores';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, address, city) VALUES (:name, :address, :city)");
        $stmt->execute([
            'name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city']
        ]);
        return $this->conn->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET name = :name, address = :address, city = :city WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city']
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
