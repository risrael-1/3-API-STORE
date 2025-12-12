<?php
require_once __DIR__ . '/../config/database.php';

class Store {
    private $conn;
    private $table = 'stores';

    public function __construct() {
        // Initialize database connection
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Retrieve all stores with optional filters and sorting
    public function getAll($filters = [], $sort = null) {
        $query = "SELECT * FROM " . $this->table;
        $params = [];

        // Filtering
        if (!empty($filters)) {
            $filterClauses = [];
            foreach ($filters as $key => $value) {
                $filterClauses[] = "$key = :$key";
                $params[$key] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $filterClauses);
        }

        // Sorting (ensure column is allowed to prevent SQL injection)
        $allowedSortColumns = ['id', 'name', 'city', 'created_at'];
        if ($sort && in_array($sort, $allowedSortColumns)) {
            $query .= " ORDER BY " . $sort;
        }

        $stmt = $this->conn->prepare($query);

        // Bind parameters to prevent SQL injection
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new store
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (name, address, city) VALUES (:name, :address, :city)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':city', $data['city']);
        
        $stmt->execute();

        // Return the ID of the newly created store
        return $this->conn->lastInsertId();
    }
}