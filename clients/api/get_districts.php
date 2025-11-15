<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../repos/Location.php';

$provinceId = isset($_GET['province_id']) ? (int)$_GET['province_id'] : 0;
$districts = [];

if ($provinceId > 0) {
    $locationRepo = new Location();
    $districts = $locationRepo->getDistrictsByProvinceId($provinceId);
}

echo json_encode($districts);
?>