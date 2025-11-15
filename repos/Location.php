<?php
require_once __DIR__ . '/../config/connect.php';

class Location {
    private $pdo;
    
    public function __construct() {
        global $conn;
        $this->pdo = $conn;
    }

    public function getProvinces() {
        $stmt = $this->pdo->query("SELECT id, name FROM provinces ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistrictsByProvinceId($province_id) {
        $stmt = $this->pdo->prepare("SELECT id, name FROM districts WHERE province_id = ? ORDER BY name ASC");
        $stmt->execute([$province_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommunesByDistrictId($district_id) {
        $stmt = $this->pdo->prepare("SELECT id, name FROM communes WHERE district_id = ? ORDER BY name ASC");
        $stmt->execute([$district_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVillagesByCommuneId($commune_id) {
        $stmt = $this->pdo->prepare("SELECT id, name FROM villages WHERE commune_id = ? ORDER BY name ASC");
        $stmt->execute([$commune_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
