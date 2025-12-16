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

    // public function getUsersForAdmin() {
    //     $stmt = $this->pdo->query("SELECT id, username FROM users");
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    // In repos/Users.php
    public function getUsersForAdmin() {
        // Select all important fields
        $stmt = $this->pdo->query("SELECT id, username, gmail, isAdmin, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDb() {
        return $this->pdo;
    }

    public function getUserById($userId) {
        $stmt = $this->pdo->prepare("SELECT id, username, gmail, created_at FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isEmailTakenByOtherUser($gmail, $userId) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE gmail = :gmail AND id != :userId");
        $stmt->execute(['gmail' => $gmail, 'userId' => $userId]);
        if ($stmt->fetch()) {
            return true;
        }
        return false;
    }

    public function updateUserEmail($userId, $user_gmail) {
        $stmt = $this->pdo->prepare("UPDATE users SET gmail = :user_gmail WHERE id = :userId");
        if ($stmt->execute([
            'user_gmail' => $user_gmail,
            'userId' => $userId
        ])) {
            return true;
        }
        return "An error occurred while updating your email.";
    }

    // Add these methods to repos/Users.php

    public function updateUserRole($userId, $isAdmin) {
        $stmt = $this->pdo->prepare("UPDATE users SET isAdmin = :isAdmin WHERE id = :id");
        return $stmt->execute([
            'isAdmin' => $isAdmin, // 1 or 0
            'id' => $userId
        ]);
    }

    public function deleteUser($userId) {
        $this->pdo->beginTransaction();
        try {
            // Optional: Delete related data first (like profile, orders) to avoid foreign key errors
            // For now, we'll just try to delete the user. 
            // If you have foreign keys set to CASCADE in your SQL, this works automatically.
            // Otherwise, you might need to delete from user_profile and orders first.
            
            $stmt = $this->pdo->prepare("DELETE FROM user_profile WHERE user_id = ?");
            $stmt->execute([$userId]);

            $stmt = $this->pdo->prepare("DELETE FROM orders WHERE userId = ?");
            $stmt->execute([$userId]);

            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}