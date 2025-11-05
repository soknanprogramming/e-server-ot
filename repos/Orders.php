<!-- userId, Username, PhoneNumber, categories_problemId, Problem, imageId, latitude, longitude, -->

<?php
require_once __DIR__ . '/../config/connect.php';

class Orders {
    private $pdo;

    public function __construct() {
        global $conn;
        $this->pdo = $conn;
    }

    public function createOrder($data): bool {
        $stmt = $this->pdo->prepare("INSERT INTO orders 
            (userId, Username, PhoneNumber, categories_problemId, Problem, imageId,
             latitude, longitude, province_id, district_id, commune_id, villages_id, 
             IsHelp, IsAdminSee) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)");

        return $stmt->execute([
            $data['userId'],
            $data['username'],
            $data['phone'],
            $data['problemId'],
            $data['problemTxt'],
            $data['imageId'],
            $data['latitude'],
            $data['longitude'],
            $data['province'],
            $data['district'],
            $data['commune'],
            $data['village']
        ]);
    }

    public function getAllOrders() {
        $stmt = $this->pdo->query("SELECT * FROM orders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdersByCategoryOrderId($categoryOrderId) {
        $stmt = $this->pdo->prepare("
            SELECT o.*, cp.name AS problemName, co.name AS categoryName
            FROM orders o
            JOIN categories_problem cp ON o.categories_problemId = cp.id
            JOIN categories_order co ON cp.categories_orderId = co.id
            WHERE co.id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$categoryOrderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOrdersById($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}