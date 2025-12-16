<?php
session_start();
require_once __DIR__ . '/../repos/Orders.php';

// Security Check
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: /auth/sign_in.php");
    exit;
}

$orderId = $_GET['id'] ?? 0;
$ordersRepo = new Orders();
// We can reuse getOrderDetailById, but we pass the userId from the order itself, 
// OR we create a new getOrderDetailForAdmin($id). 
// For simplicity, let's use the generic getAllOrdersDetailed logic but filtered for one ID.
$allOrders = $ordersRepo->getAllOrdersDetailed(); 
$order = null;
foreach($allOrders as $o) {
    if ($o['id'] == $orderId) {
        $order = $o;
        break;
    }
}

if (!$order) {
    die("Order not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order['id'] ?> Details</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #f3f4f6; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { border-bottom: 1px solid #eee; padding-bottom: 1rem; }
        .row { display: flex; margin-bottom: 1rem; }
        .label { width: 150px; font-weight: bold; color: #555; }
        .value { flex: 1; }
        .images { display: flex; gap: 10px; margin-top: 1rem; flex-wrap: wrap; }
        .images img { max-width: 200px; border-radius: 4px; border: 1px solid #ddd; }
        .back-btn { display: inline-block; margin-bottom: 1rem; text-decoration: none; color: #2563eb; }
    </style>
</head>
<body>
    <a href="/admin/index.php" class="back-btn">&larr; Back to Dashboard</a>
    
    <div class="container">
        <h1>Order Details #<?= $order['id'] ?></h1>
        
        <div class="row">
            <div class="label">Customer:</div>
            <div class="value"><?= htmlspecialchars($order['Username']) ?> (<?= htmlspecialchars($order['PhoneNumber']) ?>)</div>
        </div>
        
        <div class="row">
            <div class="label">Category:</div>
            <div class="value"><?= htmlspecialchars($order['category_name']) ?></div>
        </div>

        <div class="row">
            <div class="label">Problem:</div>
            <div class="value"><?= htmlspecialchars($order['problem_name']) ?></div>
        </div>
        
        <div class="row">
            <div class="label">Description:</div>
            <div class="value"><?= nl2br(htmlspecialchars($order['Problem'])) ?></div>
        </div>

        <div class="row">
            <div class="label">Location:</div>
            <div class="value">
                <?= htmlspecialchars($order['province'] ?? '') ?>, 
                <?= htmlspecialchars($order['district'] ?? '') ?>, 
                <?= htmlspecialchars($order['commune'] ?? '') ?>, 
                <?= htmlspecialchars($order['village'] ?? '') ?>
            </div>
        </div>
        
        <div class="row">
            <div class="label">Date:</div>
            <div class="value"><?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></div>
        </div>

        <?php if (!empty($order['image1'])): ?>
            <h3>Images</h3>
            <div class="images">
                <img src="../assets/images/<?= htmlspecialchars($order['image1']) ?>" alt="Order Image">
            </div>
        <?php endif; ?>
    </div>
</body>
</html>