<?php
session_start();
require_once __DIR__ . '/../../repos/Orders.php';

// Security: Admin Only
if (!isset($_SESSION['user_id']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: /auth/sign_in.php");
    exit;
}

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    $ordersRepo = new Orders();
    $ordersRepo->deleteOrderAsAdmin($orderId);
}

// Redirect back to dashboard
header("Location: /admin/index.php");
exit;