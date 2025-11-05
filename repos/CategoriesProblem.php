<?php
require_once __DIR__ . '/../config/connect.php';

class CategoriesProblem {
    private $pdo;

    public function __construct() {
        global $conn;
        $this->pdo = $conn;
    }

    public function getProblemsByCategoryOrderId($categoryOrderId) {
        $stmt = $this->pdo->prepare("SELECT id, name FROM categories_problem WHERE categories_orderId = ?");
        $stmt->execute([$categoryOrderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}