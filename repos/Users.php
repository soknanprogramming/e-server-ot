<?php
require_once __DIR__ . '/../config/connect.php';

class Users {
    private $pdo;

    public function __construct() {
        global $conn;
        $this->pdo = $conn;
    }

    public function userLogin($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function userSignup($username, $password, $gmail) {
        // Check for existing user
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username OR gmail = :gmail LIMIT 1");
        $stmt->execute(['username' => $username, 'gmail' => $gmail]);
        if ($stmt->fetch()) {
            return "Username or email already exists.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, gmail) VALUES (:username, :password, :gmail)");
        if ($stmt->execute([
            'username' => $username,
            'password' => $hashedPassword,
            'gmail' => $gmail
        ])) {
            return true;
        }
        return "An error occurred during signup.";
    }

    public function getUsersForAdmin() {
        $stmt = $this->pdo->query("SELECT id, username FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}