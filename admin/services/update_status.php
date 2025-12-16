<?php
session_start();
require_once __DIR__ . '/../../repos/Orders.php';

// Security check
if (!isset($_SESSION['user_id']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: /auth/sign_in.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $orderId = $_GET['id'];
    $status = (int)$_GET['status']; // 1 for Completed, 0 for Pending

    $ordersRepo = new Orders();
    $ordersRepo->updateOrderStatus($orderId, $status);
}

// Redirect back to the dashboard
header("Location: /admin/index.php");
exit;