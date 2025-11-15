<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../repos/Location.php';

$communeId = isset($_GET['commune_id']) ? (int)$_GET['commune_id'] : 0;
$villages = [];

if ($communeId > 0) {
    $locationRepo = new Location();
    $villages = $locationRepo->getVillagesByCommuneId($communeId);
}

echo json_encode($villages);
?>