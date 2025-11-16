<?php
require_once __DIR__ . '/../config/connect.php';

class ContactMessages {
    private $pdo;

    public function __construct() {
        global $conn;
        $this->pdo = $conn;
    }

    public function createMessage($name, $email, $subject, $message): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)"
        );
        
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':subject' => $subject,
            ':message' => $message
        ]);
    }
}