<?php
require_once '../../config/check_login.php';
require_once '../../repos/Orders.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /clients/ordered.php');
    exit();
}

$orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$userId = $_SESSION['user_id'];

if ($orderId <= 0) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid order ID.'];
    header('Location: /clients/ordered.php');
    exit();
}

$orderRepo = new Orders();
$success = $orderRepo->deleteOrderByIdAndUserId($orderId, $userId);

if ($success) {
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Order has been successfully deleted.'];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete the order. It may have already been removed or you do not have permission.'];
}

header('Location: /clients/ordered.php');
exit();