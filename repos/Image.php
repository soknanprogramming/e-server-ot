<?php
require_once __DIR__ . '/../config/connect.php';

class Image {
    private $pdo;

    public function __construct() {
        global $conn;
        $this->pdo = $conn;
    }

    public function saveImages(array $images): int {
        $stmt = $this->pdo->prepare("INSERT INTO image (image1, image2, image3, image4, image5) 
                                     VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $images[0] ?? null,
            $images[1] ?? null,
            $images[2] ?? null,
            $images[3] ?? null,
            $images[4] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }
}
