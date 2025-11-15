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

    public function getOrdersByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT
                o.id, o.Problem, o.created_at, o.IsHelp,
                cp.name AS problem_name,
                co.name AS category_name,
                p.name AS province,
                d.name AS district,
                c.name AS commune,
                v.name AS village,
                i.image1
            FROM orders o
            JOIN categories_problem cp ON o.categories_problemId = cp.id
            JOIN categories_order co ON cp.categories_orderId = co.id
            LEFT JOIN provinces p ON o.province_id = p.id
            LEFT JOIN districts d ON o.district_id = d.id
            LEFT JOIN communes c ON o.commune_id = c.id
            LEFT JOIN villages v ON o.villages_id = v.id
            LEFT JOIN image i ON o.imageId = i.id
            WHERE o.userId = ? ORDER BY o.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetailById($orderId, $userId) {
        $stmt = $this->pdo->prepare("
            SELECT
                o.id, o.Problem, o.created_at, o.IsHelp, o.latitude, o.longitude,
                cp.name AS problem_name,
                co.name AS category_name,
                p.name AS province,
                d.name AS district,
                c.name AS commune,
                v.name AS village,
                i.image1, i.image2, i.image3, i.image4, i.image5
            FROM orders o
            JOIN categories_problem cp ON o.categories_problemId = cp.id
            JOIN categories_order co ON cp.categories_orderId = co.id
            LEFT JOIN provinces p ON o.province_id = p.id
            LEFT JOIN districts d ON o.district_id = d.id
            LEFT JOIN communes c ON o.commune_id = c.id
            LEFT JOIN villages v ON o.villages_id = v.id
            LEFT JOIN image i ON o.imageId = i.id
            WHERE o.id = ? AND o.userId = ?
        ");
        $stmt->execute([$orderId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteOrderByIdAndUserId($orderId, $userId) {
        $this->pdo->beginTransaction();
        try {
            // Find the order to get the imageId
            $stmt = $this->pdo->prepare("SELECT imageId FROM orders WHERE id = ? AND userId = ?");
            $stmt->execute([$orderId, $userId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                // Order not found or doesn't belong to the user
                $this->pdo->rollBack();
                return false;
            }

            // Delete the order first
            $stmt = $this->pdo->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->execute([$orderId]);

            // If there was an associated image record, delete it and the files
            if ($order['imageId']) {
                // This assumes you have an Image repository to handle image file deletion
                // For now, we just delete the DB record.
                // A more robust solution would also delete files from the server.
                $stmt = $this->pdo->prepare("DELETE FROM image WHERE id = ?");
                $stmt->execute([$order['imageId']]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            // Log the error: error_log($e->getMessage());
            return false;
        }
    }
}