<?php
require_once __DIR__ . '/../config/connect.php';

class CategoriesOrders {
    private $pdo;

    public function __construct() {
        global $conn;
        $this->pdo = $conn;
    }

    public function getCategoriesOrders() {
        $stmt = $this->pdo->query("SELECT id, name, imageURL, created_at FROM categories_order ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}