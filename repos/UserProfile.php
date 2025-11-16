<?php
require_once __DIR__ . '/../config/connect.php';

class UserProfile {
    private $pdo;

    public function __construct() {
        global $conn;
        $this->pdo = $conn;
    }

    public function getProfileByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                up.ImageURL, up.gmail AS profile_gmail, up.phone, up.phone2, up.location, up.sexId, up.Bio,
                u.username, u.gmail AS user_gmail, u.created_at,
                s.name as sex_name
            FROM users u
            LEFT JOIN user_profile up ON u.id = up.user_id
            LEFT JOIN sex s ON up.sexId = s.id
            WHERE u.id = ?
        ");
        $stmt->execute([$userId]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profile) {
            // If no profile exists, get basic user info
            $stmt = $this->pdo->prepare("SELECT id, username, gmail, created_at FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $profile;
    }

    public function upsertProfile($userId, $data) {
        $stmt = $this->pdo->prepare("SELECT id FROM user_profile WHERE user_id = ?");
        $stmt->execute([$userId]);
        $exists = $stmt->fetch();

        // Build the SQL query dynamically based on what's in $data
        $columns = [];
        foreach ($data as $key => $value) {
            $columns[] = "$key = :$key";
        }
        $setClause = implode(', ', $columns);

        if ($exists) {
            // Update existing profile
            $sql = "UPDATE user_profile SET $setClause WHERE user_id = :userId";
            $stmt = $this->pdo->prepare($sql);
        } else {
            // Insert new profile
            $stmt = $this->pdo->prepare("INSERT INTO user_profile (user_id, " . implode(', ', array_keys($data)) . ") VALUES (:userId, :" . implode(', :', array_keys($data)) . ")");
        }
        
        $data['userId'] = $userId;
        return $stmt->execute($data);
    }
}
?>