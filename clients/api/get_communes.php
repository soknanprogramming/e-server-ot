<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../repos/Location.php';

$districtId = isset($_GET['district_id']) ? (int)$_GET['district_id'] : 0;
$communes = [];

if ($districtId > 0) {
    $locationRepo = new Location();
    $communes = $locationRepo->getCommunesByDistrictId($districtId);
}

echo json_encode($communes);
?>